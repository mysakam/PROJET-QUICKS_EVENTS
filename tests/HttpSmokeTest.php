<?php

declare(strict_types=1);

namespace QuickEvents\Tests;

use PHPUnit\Framework\TestCase;

final class HttpSmokeTest extends TestCase
{
    private static string $frontBase;
    private static string $backBase;

    public static function setUpBeforeClass(): void
    {
        $frontInput = (string) (getenv('QE_FRONT_BASE_URL') ?: '');
        $backInput = (string) (getenv('QE_BACK_BASE_URL') ?: '');

        $frontCandidates = $frontInput !== ''
            ? [$frontInput]
            : [
                'http://localhost/PROJET-QUICKS_EVENTS/PROJET-QUICKS_EVENTS/front/public',
                'http://localhost/PROJET-QUICKS_EVENTS/front/public',
                'http://127.0.0.1/PROJET-QUICKS_EVENTS/PROJET-QUICKS_EVENTS/front/public',
                'http://127.0.0.1/PROJET-QUICKS_EVENTS/front/public',
            ];

        $backCandidates = $backInput !== ''
            ? [$backInput]
            : [
                'http://localhost/PROJET-QUICKS_EVENTS/PROJET-QUICKS_EVENTS/back/public',
                'http://localhost/PROJET-QUICKS_EVENTS/back/public',
                'http://127.0.0.1/PROJET-QUICKS_EVENTS/PROJET-QUICKS_EVENTS/back/public',
                'http://127.0.0.1/PROJET-QUICKS_EVENTS/back/public',
            ];

        self::$frontBase = self::pickReachableBase('Front', $frontCandidates);
        self::$backBase = self::pickReachableBase('Back', $backCandidates);
    }

    public function testFrontHealth(): void
    {
        $response = self::healthRequest(self::$frontBase);
        $this->assertSame('', $response['error'], 'Front health cURL error: ' . $response['error']);
        $this->assertSame(200, $response['status'], 'Front /health should return 200');
    }

    public function testBackHealth(): void
    {
        $response = self::healthRequest(self::$backBase);
        $this->assertSame('', $response['error'], 'Back health cURL error: ' . $response['error']);
        $this->assertSame(200, $response['status'], 'Back /health should return 200');
    }

    public function testFrontPreflightCors(): void
    {
        $response = self::request('OPTIONS', rtrim(self::$frontBase, '/') . '/', self::corsHeaders());
        $this->assertSame('', $response['error'], 'Front preflight cURL error: ' . $response['error']);
        $this->assertContains($response['status'], [200, 204], 'Front preflight status should be 200 or 204');
        $allowOrigin = (string) ($response['headers']['access-control-allow-origin'] ?? '');
        $this->assertNotSame('', $allowOrigin, 'Front preflight should return Access-Control-Allow-Origin');
        $this->assertStringContainsStringIgnoringCase('localhost:5173', $allowOrigin);
    }

    public function testBackPreflightCors(): void
    {
        $response = self::request('OPTIONS', rtrim(self::$backBase, '/') . '/', self::corsHeaders());
        $this->assertSame('', $response['error'], 'Back preflight cURL error: ' . $response['error']);
        $this->assertContains($response['status'], [200, 204], 'Back preflight status should be 200 or 204');
        $allowOrigin = (string) ($response['headers']['access-control-allow-origin'] ?? '');
        $this->assertNotSame('', $allowOrigin, 'Back preflight should return Access-Control-Allow-Origin');
        $this->assertStringContainsStringIgnoringCase('localhost:5173', $allowOrigin);
    }

    public function testFrontLoginPageRendersWithExpectedFields(): void
    {
        $response = self::request('GET', self::frontUrl('/login'));

        $this->assertSame('', $response['error'], 'Login page cURL error: ' . $response['error']);
        $this->assertSame(200, $response['status'], 'Login page should return 200');
        $this->assertStringContainsString('name="email"', $response['body']);
        $this->assertStringContainsString('name="password"', $response['body']);
    }

    public function testGuestIsRedirectedFromPanierToLogin(): void
    {
        $response = self::request('GET', self::frontUrl('/panier'));

        $this->assertSame('', $response['error'], 'Guest /panier cURL error: ' . $response['error']);
        $this->assertContains($response['status'], [301, 302, 303], 'Guest /panier should redirect to login');
        $this->assertStringContainsString('/login', strtolower((string) ($response['headers']['location'] ?? '')));
    }

    public function testGuestIsRedirectedFromDevisToLogin(): void
    {
        $response = self::request('GET', self::frontUrl('/devis'));

        $this->assertSame('', $response['error'], 'Guest /devis cURL error: ' . $response['error']);
        $this->assertContains($response['status'], [301, 302, 303], 'Guest /devis should redirect to login');
        $this->assertStringContainsString('/login', strtolower((string) ($response['headers']['location'] ?? '')));
    }

    public function testGuestIsRedirectedFromAdminPrestatairesToLogin(): void
    {
        $response = self::request('GET', self::frontUrl('/admin/prestataires'));

        $this->assertSame('', $response['error'], 'Guest /admin/prestataires cURL error: ' . $response['error']);
        $this->assertContains($response['status'], [301, 302, 303], 'Guest /admin/prestataires should redirect to login');
        $this->assertStringContainsString('/login', strtolower((string) ($response['headers']['location'] ?? '')));
    }

    public function testGuestIsRedirectedFromAdminEventMediasToLogin(): void
    {
        $response = self::request('GET', self::frontUrl('/admin/event-medias'));

        $this->assertSame('', $response['error'], 'Guest /admin/event-medias cURL error: ' . $response['error']);
        $this->assertContains($response['status'], [301, 302, 303], 'Guest /admin/event-medias should redirect to login');
        $this->assertStringContainsString('/login', strtolower((string) ($response['headers']['location'] ?? '')));
    }

    public function testGuestIsRedirectedFromAdminPrestataireDetailToLogin(): void
    {
        $response = self::request('GET', self::frontUrl('/admin/prestataires/1'));

        $this->assertSame('', $response['error'], 'Guest /admin/prestataires/1 cURL error: ' . $response['error']);
        $this->assertContains($response['status'], [301, 302, 303], 'Guest /admin/prestataires/1 should redirect to login');
        $this->assertStringContainsString('/login', strtolower((string) ($response['headers']['location'] ?? '')));
    }

    public function testAuthenticatedClientJourneyLoginEventThenPanierAndDevis(): void
    {
        $email = (string) (getenv('QE_TEST_EMAIL') ?: '');
        $password = (string) (getenv('QE_TEST_PASSWORD') ?: '');

        if ($email === '' || $password === '') {
            $this->markTestSkipped('Set QE_TEST_EMAIL and QE_TEST_PASSWORD to enable authenticated client flow tests.');
        }

        $cookieJar = [];
        $csrfToken = self::loadLoginPageAndGetToken($cookieJar);

        $loginPost = self::request(
            'POST',
            self::frontUrl('/login'),
            ['X-CSRF-Token: ' . $csrfToken],
            [
                'email' => $email,
                'password' => $password,
                '_csrf_token' => $csrfToken,
            ],
            $cookieJar
        );

        $this->assertSame('', $loginPost['error'], 'Login POST cURL error: ' . $loginPost['error']);
        $this->assertContains($loginPost['status'], [301, 302, 303], 'Login POST should redirect.');

        $loginLocation = strtolower((string) ($loginPost['headers']['location'] ?? ''));
        $this->assertStringNotContainsString('/login', $loginLocation, 'Login failed: still redirected to /login. Check QE_TEST_EMAIL/QE_TEST_PASSWORD.');

        $panierBeforeEvent = self::request('GET', self::frontUrl('/panier'), [], null, $cookieJar);
        $this->assertContains($panierBeforeEvent['status'], [301, 302, 303], 'Authenticated /panier should redirect to /mon-evenement until event form is completed.');
        $this->assertStringContainsString('/mon-evenement', strtolower((string) ($panierBeforeEvent['headers']['location'] ?? '')));

        $eventPage = self::request('GET', self::frontUrl('/mon-evenement'), [], null, $cookieJar);
        $this->assertSame(200, $eventPage['status'], 'GET /mon-evenement should be accessible when authenticated.');

        $eventCsrf = self::extractCsrfToken($eventPage['body']);
        $this->assertNotSame('', $eventCsrf, 'CSRF token should be present on /mon-evenement page.');

        $eventPost = self::request(
            'POST',
            self::frontUrl('/mon-evenement'),
            ['X-CSRF-Token: ' . $eventCsrf],
            [
                'type_evenement' => 'mariage',
                'nb_personnes' => '80',
                'budget' => '5000',
                '_csrf_token' => $eventCsrf,
            ],
            $cookieJar
        );

        $this->assertContains($eventPost['status'], [301, 302, 303], 'POST /mon-evenement should redirect after save.');

        $panierAfterEvent = self::request('GET', self::frontUrl('/panier'), [], null, $cookieJar);
        $this->assertSame('', $panierAfterEvent['error'], 'Authenticated /panier after event cURL error: ' . $panierAfterEvent['error']);
        $this->assertSame(200, $panierAfterEvent['status'], 'Authenticated /panier should be accessible after event form save.');

        $devisIndex = self::request('GET', self::frontUrl('/devis'), [], null, $cookieJar);
        $this->assertSame(200, $devisIndex['status'], 'Authenticated /devis should be accessible.');
    }

    public function testAuthenticatedAdminCanAccessPrestatairesAndMedias(): void
    {
        $email = (string) (getenv('QE_ADMIN_EMAIL') ?: '');
        $password = (string) (getenv('QE_ADMIN_PASSWORD') ?: '');
        $prestataireId = max(1, (int) (getenv('QE_ADMIN_PRESTATAIRE_ID') ?: '1'));

        if ($email === '' || $password === '') {
            $this->markTestSkipped('Set QE_ADMIN_EMAIL and QE_ADMIN_PASSWORD to enable authenticated admin flow tests.');
        }

        $cookieJar = [];
        $csrfToken = self::loadLoginPageAndGetToken($cookieJar);

        $loginPost = self::request(
            'POST',
            self::frontUrl('/login'),
            ['X-CSRF-Token: ' . $csrfToken],
            [
                'email' => $email,
                'password' => $password,
                '_csrf_token' => $csrfToken,
            ],
            $cookieJar
        );

        $this->assertSame('', $loginPost['error'], 'Admin login POST cURL error: ' . $loginPost['error']);
        $this->assertContains($loginPost['status'], [301, 302, 303], 'Admin login POST should redirect.');
        $this->assertStringNotContainsString('/login', strtolower((string) ($loginPost['headers']['location'] ?? '')), 'Admin login failed. Check QE_ADMIN_EMAIL/QE_ADMIN_PASSWORD.');

        $prestataires = self::request('GET', self::frontUrl('/admin/prestataires'), [], null, $cookieJar);
        $this->assertSame('', $prestataires['error'], 'Admin /admin/prestataires cURL error: ' . $prestataires['error']);
        $this->assertSame(200, $prestataires['status'], 'Admin /admin/prestataires should return 200.');

        $prestataireDetail = self::request('GET', self::frontUrl('/admin/prestataires/' . $prestataireId), [], null, $cookieJar);
        $this->assertSame('', $prestataireDetail['error'], 'Admin /admin/prestataires/{id} cURL error: ' . $prestataireDetail['error']);
        $this->assertSame(200, $prestataireDetail['status'], 'Admin /admin/prestataires/{id} should return 200.');

        $medias = self::request('GET', self::frontUrl('/admin/event-medias'), [], null, $cookieJar);
        $this->assertSame('', $medias['error'], 'Admin /admin/event-medias cURL error: ' . $medias['error']);
        $this->assertSame(200, $medias['status'], 'Admin /admin/event-medias should return 200.');

        $mediaCreate = self::request('GET', self::frontUrl('/admin/event-medias/create'), [], null, $cookieJar);
        $this->assertSame('', $mediaCreate['error'], 'Admin /admin/event-medias/create cURL error: ' . $mediaCreate['error']);
        $this->assertSame(200, $mediaCreate['status'], 'Admin /admin/event-medias/create should return 200.');
    }

    private static function loadLoginPageAndGetToken(array &$cookieJar): string
    {
        $loginPage = self::request('GET', self::frontUrl('/login'), [], null, $cookieJar);
        self::assertSame(200, $loginPage['status'], 'GET /login should return 200 before authentication.');

        $csrfToken = self::extractCsrfToken($loginPage['body']);
        self::assertNotSame('', $csrfToken, 'CSRF token should be present in login page HTML.');

        return $csrfToken;
    }

    private static function extractCsrfToken(string $html): string
    {
        if (preg_match('/<meta\s+name="csrf-token"\s+content="([^"]+)"/i', $html, $matches) === 1) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5);
        }

        if (preg_match('/name="_csrf_token"\s+value="([^"]+)"/i', $html, $matches) === 1) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5);
        }

        return '';
    }

    private static function frontUrl(string $path): string
    {
        return rtrim(self::$frontBase, '/') . $path;
    }

    private static function healthRequest(string $baseUrl): array
    {
        $baseUrl = rtrim($baseUrl, '/');
        $candidates = [
            $baseUrl . '/health',
            $baseUrl . '/index.php/health',
        ];

        foreach ($candidates as $candidate) {
            $response = self::request('GET', $candidate);
            if ($response['error'] === '' && $response['status'] === 200) {
                return $response;
            }
        }

        return self::request('GET', $candidates[count($candidates) - 1]);
    }

    private static function pickReachableBase(string $label, array $candidates): string
    {
        foreach ($candidates as $candidate) {
            $candidate = rtrim((string) $candidate, '/');
            if ($candidate === '') {
                continue;
            }

            $response = self::request('OPTIONS', $candidate . '/', self::corsHeaders());
            $allowMethods = (string) ($response['headers']['access-control-allow-methods'] ?? '');

            if (
                $response['error'] === ''
                && in_array($response['status'], [200, 204], true)
                && str_contains(strtolower($allowMethods), 'options')
            ) {
                return $candidate;
            }
        }

        self::fail($label . ' base URL not reachable. Set QE_' . strtoupper($label) . '_BASE_URL to a valid URL.');
        return '';
    }

    private static function corsHeaders(): array
    {
        return [
            'Origin: http://localhost:5173',
            'Access-Control-Request-Method: POST',
            'Access-Control-Request-Headers: Content-Type, X-CSRF-Token',
        ];
    }

    /**
     * @param array<string> $headers
     * @param array<string, string>|null $postFields
     * @param array<string, string> $cookieJar
     *
     * @return array{status:int,headers:array<string,string>,body:string,error:string}
     */
    private static function request(
        string $method,
        string $url,
        array $headers = [],
        ?array $postFields = null,
        array &$cookieJar = []
    ): array {
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
    }
}