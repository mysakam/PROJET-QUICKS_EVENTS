<?php

declare(strict_types=1);

function request(string $method, string $url, array $headers = []): array
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);

    if ($headers !== []) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'status' => $status,
        'body' => is_string($body) ? $body : '',
        'error' => $error,
    ];
}

function assertStatus(string $label, int $expected, array $response): bool
{
    if ($response['error'] !== '') {
        echo "[FAIL] {$label} - erreur cURL: {$response['error']}" . PHP_EOL;
        return false;
    }

    if ($response['status'] !== $expected) {
        echo "[FAIL] {$label} - attendu {$expected}, obtenu {$response['status']}" . PHP_EOL;
        return false;
    }

    echo "[OK] {$label}" . PHP_EOL;
    return true;
}

$frontBase = $argv[1] ?? 'http://localhost/PROJET-QUICKS_EVENTS/front/public';
$backBase = $argv[2] ?? 'http://localhost/PROJET-QUICKS_EVENTS/back/public';

$testsOk = true;

$testsOk = assertStatus('Front health', 200, request('GET', rtrim($frontBase, '/') . '/health')) && $testsOk;
$testsOk = assertStatus('Back health', 200, request('GET', rtrim($backBase, '/') . '/health')) && $testsOk;

$corsHeaders = [
    'Origin: http://localhost:5173',
    'Access-Control-Request-Method: POST',
    'Access-Control-Request-Headers: Content-Type, X-CSRF-Token',
];

$testsOk = assertStatus('Front preflight CORS', 204, request('OPTIONS', rtrim($frontBase, '/') . '/', $corsHeaders)) && $testsOk;
$testsOk = assertStatus('Back preflight CORS', 204, request('OPTIONS', rtrim($backBase, '/') . '/', $corsHeaders)) && $testsOk;

if (!$testsOk) {
    exit(1);
}

echo 'Tous les smoke tests sont passes.' . PHP_EOL;
