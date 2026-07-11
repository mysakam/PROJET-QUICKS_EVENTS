<?php

declare(strict_types=1);

$root = dirname(__DIR__);

$mode = strtolower((string) ($argv[1] ?? 'bootstrap'));

if ($mode === 'help-env') {
    echo "Variables utiles pour les scenarios connectes:\n";
    echo "  QE_TEST_EMAIL=...\n";
    echo "  QE_TEST_PASSWORD=...\n";
    echo "  QE_ADMIN_EMAIL=...\n";
    echo "  QE_ADMIN_PASSWORD=...\n";
    echo "  QE_ADMIN_PRESTATAIRE_ID=1\n";
    echo "\nExemple Git Bash:\n";
    echo "  export QE_TEST_EMAIL=\"samy@test.com\"\n";
    echo "  export QE_TEST_PASSWORD=\"...\"\n";
    echo "  export QE_ADMIN_EMAIL=\"samy@test.com\"\n";
    echo "  export QE_ADMIN_PASSWORD=\"...\"\n";
    echo "  php.exe tools/bootstrap_local.php functional\n";
    exit(0);
}

if ($mode === 'functional') {
    $frontInput = $argv[2] ?? '';
    $backInput = $argv[3] ?? '';

    $request = static function (string $method, string $url, array $headers = [], ?array $postFields = null, array &$cookieJar = []): array {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $effectiveHeaders = $headers;

        if ($postFields !== null) {
            $effectiveHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        }

        if ($cookieJar !== []) {
            $pairs = [];
            foreach ($cookieJar as $name => $value) {
                $pairs[] = $name . '=' . $value;
            }
            curl_setopt($ch, CURLOPT_COOKIE, implode('; ', $pairs));
        }

        if ($effectiveHeaders !== []) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $effectiveHeaders);
        }

        $raw = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $error = curl_error($ch);
        curl_close($ch);

        $raw = is_string($raw) ? $raw : '';
        $rawHeaders = $headerSize > 0 ? substr($raw, 0, $headerSize) : '';
        $body = $headerSize > 0 ? substr($raw, $headerSize) : $raw;

        $responseHeaders = [];
        foreach (preg_split('/\r\n|\n|\r/', (string) $rawHeaders) as $line) {
            $line = trim($line);
            if ($line === '' || !str_contains($line, ':')) {
                continue;
            }

            [$name, $value] = array_map('trim', explode(':', $line, 2));
            $lowerName = strtolower($name);
            $responseHeaders[$lowerName] = $value;

            if ($lowerName === 'set-cookie') {
                $cookiePart = explode(';', $value, 2)[0] ?? '';
                if (str_contains($cookiePart, '=')) {
                    [$cookieName, $cookieValue] = explode('=', $cookiePart, 2);
                    $cookieJar[trim($cookieName)] = trim($cookieValue);
                }
            }
        }

        return [
            'status' => $status,
            'headers' => $responseHeaders,
            'body' => $body,
            'error' => $error,
        ];
    };

    $pickReachableBase = static function (string $label, array $candidates) use ($request): string {
        foreach ($candidates as $candidate) {
            $candidate = rtrim((string) $candidate, '/');
            if ($candidate === '') {
                continue;
            }

            $response = $request('OPTIONS', $candidate . '/', [
                'Origin: http://localhost:5173',
                'Access-Control-Request-Method: POST',
                'Access-Control-Request-Headers: Content-Type, X-CSRF-Token',
            ]);

            $allowMethods = strtolower((string) ($response['headers']['access-control-allow-methods'] ?? ''));
            if ($response['error'] === '' && in_array($response['status'], [200, 204], true) && str_contains($allowMethods, 'options')) {
                return $candidate;
            }
        }

        fwrite(STDERR, "[FAIL] {$label}: aucune base URL joignable\n");
        exit(1);
    };

    $extractCsrfToken = static function (string $html): string {
        if (preg_match('/<meta\s+name="csrf-token"\s+content="([^"]+)"/i', $html, $matches) === 1) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5);
        }
        if (preg_match('/name="_csrf_token"\s+value="([^"]+)"/i', $html, $matches) === 1) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5);
        }
        return '';
    };

    $healthRequest = static function (string $baseUrl) use ($request): array {
        $baseUrl = rtrim($baseUrl, '/');
        $candidates = [$baseUrl . '/health', $baseUrl . '/index.php/health'];
        foreach ($candidates as $candidate) {
            $response = $request('GET', $candidate);
            if ($response['error'] === '' && $response['status'] === 200) {
                return $response;
            }
        }
        return $request('GET', $candidates[count($candidates) - 1]);
    };

    $ok = static function (string $label): void {
        echo "[OK] {$label}\n";
    };
    $skip = static function (string $label, string $reason): void {
        echo "[SKIP] {$label} - {$reason}\n";
    };
    $fail = static function (string $label, string $message): never {
        fwrite(STDERR, "[FAIL] {$label} - {$message}\n");
        exit(1);
    };
    $assertStatus = static function (string $label, array $response, array $expectedStatuses) use ($fail): void {
        if ($response['error'] !== '') {
            $fail($label, 'erreur cURL: ' . $response['error']);
        }
        if (!in_array($response['status'], $expectedStatuses, true)) {
            $fail($label, 'statut inattendu ' . $response['status'] . ', attendu: ' . implode('/', $expectedStatuses));
        }
    };
    $assertBodyContains = static function (string $label, array $response, string $needle) use ($fail): void {
        if (!str_contains((string) $response['body'], $needle)) {
            $fail($label, 'contenu attendu introuvable: ' . $needle);
        }
    };
    $assertLocationContains = static function (string $label, array $response, string $needle) use ($fail): void {
        $location = strtolower((string) ($response['headers']['location'] ?? ''));
        if (!str_contains($location, strtolower($needle))) {
            $fail($label, 'redirection attendue vers ' . $needle . ', obtenue: ' . $location);
        }
    };
    $frontUrl = static function (string $frontBase, string $path): string {
        return rtrim($frontBase, '/') . $path;
    };

    $frontCandidates = $frontInput !== '' ? [$frontInput] : [
        'http://localhost/PROJET-QUICKS_EVENTS/PROJET-QUICKS_EVENTS/front/public',
        'http://localhost/PROJET-QUICKS_EVENTS/front/public',
        'http://127.0.0.1/PROJET-QUICKS_EVENTS/PROJET-QUICKS_EVENTS/front/public',
        'http://127.0.0.1/PROJET-QUICKS_EVENTS/front/public',
    ];
    $backCandidates = $backInput !== '' ? [$backInput] : [
        'http://localhost/PROJET-QUICKS_EVENTS/PROJET-QUICKS_EVENTS/back/public',
        'http://localhost/PROJET-QUICKS_EVENTS/back/public',
        'http://127.0.0.1/PROJET-QUICKS_EVENTS/PROJET-QUICKS_EVENTS/back/public',
        'http://127.0.0.1/PROJET-QUICKS_EVENTS/back/public',
    ];

    $frontBase = $pickReachableBase('Front', $frontCandidates);
    $backBase = $pickReachableBase('Back', $backCandidates);

    $frontHealth = $healthRequest($frontBase);
    $assertStatus('Front health', $frontHealth, [200]);
    $ok('Front health');

    $backHealth = $healthRequest($backBase);
    $assertStatus('Back health', $backHealth, [200]);
    $ok('Back health');

    $corsHeaders = [
        'Origin: http://localhost:5173',
        'Access-Control-Request-Method: POST',
        'Access-Control-Request-Headers: Content-Type, X-CSRF-Token',
    ];
    $frontCors = $request('OPTIONS', $frontBase . '/', $corsHeaders);
    $assertStatus('Front preflight CORS', $frontCors, [200, 204]);
    $ok('Front preflight CORS');
    $backCors = $request('OPTIONS', $backBase . '/', $corsHeaders);
    $assertStatus('Back preflight CORS', $backCors, [200, 204]);
    $ok('Back preflight CORS');

    $loginPage = $request('GET', $frontUrl($frontBase, '/login'));
    $assertStatus('Page login', $loginPage, [200]);
    $assertBodyContains('Page login email', $loginPage, 'name="email"');
    $assertBodyContains('Page login password', $loginPage, 'name="password"');
    $ok('Page login');

    $guestPanier = $request('GET', $frontUrl($frontBase, '/panier'));
    $assertStatus('Invite panier redirect', $guestPanier, [301, 302, 303]);
    $assertLocationContains('Invite panier redirect', $guestPanier, '/login');
    $ok('Invite panier redirect');

    $guestDevis = $request('GET', $frontUrl($frontBase, '/devis'));
    $assertStatus('Invite devis redirect', $guestDevis, [301, 302, 303]);
    $assertLocationContains('Invite devis redirect', $guestDevis, '/login');
    $ok('Invite devis redirect');

    $guestPrestataires = $request('GET', $frontUrl($frontBase, '/admin/prestataires'));
    $assertStatus('Invite admin prestataires redirect', $guestPrestataires, [301, 302, 303]);
    $assertLocationContains('Invite admin prestataires redirect', $guestPrestataires, '/login');
    $ok('Invite admin prestataires redirect');

    $guestPrestataireDetail = $request('GET', $frontUrl($frontBase, '/admin/prestataires/1'));
    $assertStatus('Invite admin prestataire detail redirect', $guestPrestataireDetail, [301, 302, 303]);
    $assertLocationContains('Invite admin prestataire detail redirect', $guestPrestataireDetail, '/login');
    $ok('Invite admin prestataire detail redirect');

    $guestMedias = $request('GET', $frontUrl($frontBase, '/admin/event-medias'));
    $assertStatus('Invite admin medias redirect', $guestMedias, [301, 302, 303]);
    $assertLocationContains('Invite admin medias redirect', $guestMedias, '/login');
    $ok('Invite admin medias redirect');

    $clientEmail = (string) getenv('QE_TEST_EMAIL');
    $clientPassword = (string) getenv('QE_TEST_PASSWORD');

    if ($clientEmail === '' || $clientPassword === '') {
        $skip('Parcours client login/panier/devis', 'QE_TEST_EMAIL et QE_TEST_PASSWORD non definis');
    } else {
        $clientCookies = [];
        $clientLoginPage = $request('GET', $frontUrl($frontBase, '/login'), [], null, $clientCookies);
        $assertStatus('Client login page', $clientLoginPage, [200]);
        $clientCsrf = $extractCsrfToken($clientLoginPage['body']);
        if ($clientCsrf === '') {
            $fail('Client login page', 'token CSRF introuvable');
        }
        $clientLogin = $request('POST', $frontUrl($frontBase, '/login'), ['X-CSRF-Token: ' . $clientCsrf], [
            'email' => $clientEmail,
            'password' => $clientPassword,
            '_csrf_token' => $clientCsrf,
        ], $clientCookies);
        $assertStatus('Client login POST', $clientLogin, [301, 302, 303]);
        $clientLoginLocation = strtolower((string) ($clientLogin['headers']['location'] ?? ''));
        if (str_contains($clientLoginLocation, '/login')) {
            $fail('Client login POST', 'retour sur /login, identifiants probablement invalides');
        }
        $ok('Client login POST');

        $clientPanierBeforeEvent = $request('GET', $frontUrl($frontBase, '/panier'), [], null, $clientCookies);
        $assertStatus('Client panier avant evenement', $clientPanierBeforeEvent, [301, 302, 303]);
        $assertLocationContains('Client panier avant evenement', $clientPanierBeforeEvent, '/mon-evenement');
        $ok('Client panier avant evenement');

        $eventPage = $request('GET', $frontUrl($frontBase, '/mon-evenement'), [], null, $clientCookies);
        $assertStatus('Client mon-evenement GET', $eventPage, [200]);
        $eventCsrf = $extractCsrfToken($eventPage['body']);
        if ($eventCsrf === '') {
            $fail('Client mon-evenement GET', 'token CSRF introuvable');
        }
        $eventPost = $request('POST', $frontUrl($frontBase, '/mon-evenement'), ['X-CSRF-Token: ' . $eventCsrf], [
            'type_evenement' => 'mariage',
            'nb_personnes' => '80',
            'budget' => '5000',
            '_csrf_token' => $eventCsrf,
        ], $clientCookies);
        $assertStatus('Client mon-evenement POST', $eventPost, [301, 302, 303]);
        $ok('Client mon-evenement POST');

        $catalogues = $request('GET', $frontUrl($frontBase, '/catalogues'), [], null, $clientCookies);
        $assertStatus('Client catalogues', $catalogues, [200]);
        $ok('Client catalogues');

        $prestation = $request('GET', $frontUrl($frontBase, '/prestations/1'), [], null, $clientCookies);
        $assertStatus('Client prestation detail', $prestation, [200]);
        $ok('Client prestation detail');

        $prestationCsrf = $extractCsrfToken($prestation['body']);
        if ($prestationCsrf === '') {
            $fail('Client prestation detail', 'token CSRF introuvable');
        }

        $addPanier = $request('POST', $frontUrl($frontBase, '/panier/ajouter/1'), ['X-CSRF-Token: ' . $prestationCsrf], [
            '_csrf_token' => $prestationCsrf,
        ], $clientCookies);
        $assertStatus('Client panier add', $addPanier, [301, 302, 303]);
        $ok('Client panier add');

        $clientPanierAfterEvent = $request('GET', $frontUrl($frontBase, '/panier'), [], null, $clientCookies);
        $assertStatus('Client panier apres evenement', $clientPanierAfterEvent, [200]);
        $ok('Client panier apres evenement');

        $checkout = $request('GET', $frontUrl($frontBase, '/devis/checkout'), [], null, $clientCookies);
        $assertStatus('Client devis checkout', $checkout, [200]);
        $ok('Client devis checkout');

        $checkoutCsrf = $extractCsrfToken($checkout['body']);
        if ($checkoutCsrf === '') {
            $fail('Client devis checkout', 'token CSRF introuvable');
        }

        $storeDevis = $request('POST', $frontUrl($frontBase, '/devis/store'), ['X-CSRF-Token: ' . $checkoutCsrf], [
            'date_evenement' => date('Y-m-d', strtotime('+30 days')),
            'message_client' => 'Test fonctionnel automatique',
            '_csrf_token' => $checkoutCsrf,
        ], $clientCookies);
        $assertStatus('Client devis store', $storeDevis, [301, 302, 303]);
        $assertLocationContains('Client devis store', $storeDevis, '/devis/success/');
        $ok('Client devis store');

        $clientDevis = $request('GET', $frontUrl($frontBase, '/devis'), [], null, $clientCookies);
        $assertStatus('Client devis', $clientDevis, [200]);
        $ok('Client devis');
    }

    $adminEmail = (string) getenv('QE_ADMIN_EMAIL');
    $adminPassword = (string) getenv('QE_ADMIN_PASSWORD');
    $adminPrestataireId = max(1, (int) (getenv('QE_ADMIN_PRESTATAIRE_ID') ?: '1'));

    if ($adminEmail === '' || $adminPassword === '') {
        $skip('Parcours admin prestataires/medias', 'QE_ADMIN_EMAIL et QE_ADMIN_PASSWORD non definis');
    } else {
        $adminCookies = [];
        $adminLoginPage = $request('GET', $frontUrl($frontBase, '/login'), [], null, $adminCookies);
        $assertStatus('Admin login page', $adminLoginPage, [200]);
        $adminCsrf = $extractCsrfToken($adminLoginPage['body']);
        if ($adminCsrf === '') {
            $fail('Admin login page', 'token CSRF introuvable');
        }
        $adminLogin = $request('POST', $frontUrl($frontBase, '/login'), ['X-CSRF-Token: ' . $adminCsrf], [
            'email' => $adminEmail,
            'password' => $adminPassword,
            '_csrf_token' => $adminCsrf,
        ], $adminCookies);
        $assertStatus('Admin login POST', $adminLogin, [301, 302, 303]);
        $adminLoginLocation = strtolower((string) ($adminLogin['headers']['location'] ?? ''));
        if (str_contains($adminLoginLocation, '/login')) {
            $fail('Admin login POST', 'retour sur /login, identifiants admin probablement invalides');
        }
        $ok('Admin login POST');

        $adminPrestataires = $request('GET', $frontUrl($frontBase, '/admin/prestataires'), [], null, $adminCookies);
        $assertStatus('Admin prestataires', $adminPrestataires, [200]);
        $ok('Admin prestataires');

        $adminPrestataireDetail = $request('GET', $frontUrl($frontBase, '/admin/prestataires/' . $adminPrestataireId), [], null, $adminCookies);
        $assertStatus('Admin prestataire detail', $adminPrestataireDetail, [200]);
        $ok('Admin prestataire detail');

        $detailCsrf = $extractCsrfToken($adminPrestataireDetail['body']);
        if ($detailCsrf === '') {
            $fail('Admin prestataire detail', 'token CSRF introuvable');
        }

        $saveMedia = $request('POST', $frontUrl($frontBase, '/admin/prestataires/' . $adminPrestataireId . '/medias'), ['X-CSRF-Token: ' . $detailCsrf], [
            '_csrf_token' => $detailCsrf,
            'media_type' => 'image',
            'media_url' => 'https://example.com/test-functional-media.jpg',
            'title_fr' => 'Media test FR',
            'title_en' => 'Media test EN',
            'description_fr' => 'Description test FR',
            'description_en' => 'Description test EN',
            'position' => '1',
            'is_active' => '1',
        ], $adminCookies);
        $assertStatus('Admin prestataire media store', $saveMedia, [301, 302, 303]);
        $ok('Admin prestataire media store');

        $adminMedias = $request('GET', $frontUrl($frontBase, '/admin/event-medias'), [], null, $adminCookies);
        $assertStatus('Admin medias', $adminMedias, [200]);
        $ok('Admin medias');

        $adminMediaCreate = $request('GET', $frontUrl($frontBase, '/admin/event-medias/create'), [], null, $adminCookies);
        $assertStatus('Admin medias create', $adminMediaCreate, [200]);
        $ok('Admin medias create');

        $mediaCreateCsrf = $extractCsrfToken($adminMediaCreate['body']);
        if ($mediaCreateCsrf === '') {
            $fail('Admin medias create', 'token CSRF introuvable');
        }

        $mediaStore = $request('POST', $frontUrl($frontBase, '/admin/event-medias'), ['X-CSRF-Token: ' . $mediaCreateCsrf], [
            '_csrf_token' => $mediaCreateCsrf,
            'theme_slug' => 'mariage',
            'media_type' => 'image',
            'media_url' => 'https://example.com/test-admin-media.jpg',
            'title_fr' => 'Media admin FR',
            'title_en' => 'Media admin EN',
            'description_fr' => 'Description admin FR',
            'description_en' => 'Description admin EN',
            'position' => '1',
            'is_active' => '1',
        ], $adminCookies);
        $assertStatus('Admin medias store', $mediaStore, [301, 302, 303]);
        $ok('Admin medias store');
    }

    echo "Tous les tests fonctionnels executables sont passes.\n";
    exit(0);
}

$composerBin = getenv('COMPOSER_BINARY');
if (!$composerBin) {
    $composerBin = 'composer';
}

$steps = [
    'Install dependencies' => $composerBin . ' install',
    'Run tests' => $composerBin . ' test',
];

foreach ($steps as $label => $command) {
    fwrite(STDOUT, "\n==> {$label}\n");
    passthru($command, $exitCode);

    if ((int) $exitCode !== 0) {
        fwrite(STDERR, "Step failed: {$label}\n");
        exit((int) $exitCode);
    }
}

fwrite(STDOUT, "\nBootstrap local completed successfully.\n");
