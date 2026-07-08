<?php

declare(strict_types=1);

function request(string $method, string $url, array $headers = []): array
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);

    if ($headers !== []) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
        $responseHeaders[strtolower($name)] = $value;
    }

    return [
        'status' => $status,
        'body' => $body,
        'headers' => $responseHeaders,
        'error' => $error,
    ];
}

function assertStatusIn(string $label, array $expectedStatuses, array $response): bool
{
    if ($response['error'] !== '') {
        echo "[FAIL] {$label} - erreur cURL: {$response['error']}" . PHP_EOL;
        return false;
    }

    if (!in_array($response['status'], $expectedStatuses, true)) {
        echo "[FAIL] {$label} - attendu " . implode('/', $expectedStatuses) . ", obtenu {$response['status']}" . PHP_EOL;
        return false;
    }

    echo "[OK] {$label}" . PHP_EOL;
    return true;
}

function assertHeaderContains(string $label, string $headerName, string $expectedValue, array $response): bool
{
    $headerValue = (string) ($response['headers'][strtolower($headerName)] ?? '');

    if ($headerValue === '') {
        echo "[FAIL] {$label} - header {$headerName} absent" . PHP_EOL;
        return false;
    }

    if ($expectedValue !== '' && !str_contains(strtolower($headerValue), strtolower($expectedValue))) {
        echo "[FAIL] {$label} - header {$headerName} invalide ({$headerValue})" . PHP_EOL;
        return false;
    }

    echo "[OK] {$label}" . PHP_EOL;
    return true;
}

function pickReachableBase(string $label, array $candidates): ?string
{
    $probeHeaders = [
        'Origin: http://localhost:5173',
        'Access-Control-Request-Method: POST',
        'Access-Control-Request-Headers: Content-Type, X-CSRF-Token',
    ];

    foreach ($candidates as $candidate) {
        $candidate = rtrim($candidate, '/');
        $response = request('OPTIONS', $candidate . '/', $probeHeaders);
        $allowMethods = (string) ($response['headers']['access-control-allow-methods'] ?? '');

        if (
            $response['error'] === ''
            && in_array($response['status'], [200, 204], true)
            && str_contains(strtolower($allowMethods), 'options')
        ) {
            echo "[OK] {$label} base detectee: {$candidate}" . PHP_EOL;
            return $candidate;
        }
    }

    echo "[FAIL] {$label} - aucune base URL joignable" . PHP_EOL;
    return null;
}

function healthCheck(string $label, string $baseUrl): bool
{
    $baseUrl = rtrim($baseUrl, '/');
    $candidates = [
        $baseUrl . '/health',
        $baseUrl . '/index.php/health',
    ];

    foreach ($candidates as $candidate) {
        $response = request('GET', $candidate);
        if ($response['error'] === '' && $response['status'] === 200) {
            echo "[OK] {$label}" . PHP_EOL;
            return true;
        }
    }

    $last = request('GET', end($candidates));
    if ($last['error'] !== '') {
        echo "[FAIL] {$label} - erreur cURL: {$last['error']}" . PHP_EOL;
    } else {
        echo "[FAIL] {$label} - endpoint /health introuvable sur les URLs candidates" . PHP_EOL;
    }

    return false;
}

$frontInput = $argv[1] ?? '';
$backInput = $argv[2] ?? '';

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

$testsOk = true;

$frontBase = pickReachableBase('Front', $frontCandidates);
$backBase = pickReachableBase('Back', $backCandidates);

if ($frontBase === null || $backBase === null) {
    exit(1);
}

$testsOk = healthCheck('Front health', $frontBase) && $testsOk;
$testsOk = healthCheck('Back health', $backBase) && $testsOk;

$corsHeaders = [
    'Origin: http://localhost:5173',
    'Access-Control-Request-Method: POST',
    'Access-Control-Request-Headers: Content-Type, X-CSRF-Token',
];

$frontPreflight = request('OPTIONS', rtrim($frontBase, '/') . '/', $corsHeaders);
$backPreflight = request('OPTIONS', rtrim($backBase, '/') . '/', $corsHeaders);

$testsOk = assertStatusIn('Front preflight CORS status', [200, 204], $frontPreflight) && $testsOk;
$testsOk = assertHeaderContains('Front preflight CORS origin', 'Access-Control-Allow-Origin', 'localhost:5173', $frontPreflight) && $testsOk;
$testsOk = assertStatusIn('Back preflight CORS status', [200, 204], $backPreflight) && $testsOk;
$testsOk = assertHeaderContains('Back preflight CORS origin', 'Access-Control-Allow-Origin', 'localhost:5173', $backPreflight) && $testsOk;

if (!$testsOk) {
    exit(1);
}

echo 'Tous les smoke tests sont passes.' . PHP_EOL;
