<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['SERVER_PORT'] ?? null) === '443');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);

ini_set('session.use_strict_mode', '1');
session_start();

if (!isset($_SESSION['_created_at'])) {
    $_SESSION['_created_at'] = time();
    $_SESSION['_last_activity'] = time();
    $_SESSION['_ua'] = hash('sha256', (string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));
}

if (isset($_SESSION['_last_activity']) && (time() - (int) $_SESSION['_last_activity']) > 1800) {
    session_regenerate_id(true);
    $_SESSION = [];
    session_destroy();
    session_start();
}

$_SESSION['_last_activity'] = time();

$currentUaHash = hash('sha256', (string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));
if (!empty($_SESSION['client']) && isset($_SESSION['_ua']) && !hash_equals((string) $_SESSION['_ua'], $currentUaHash)) {
    $_SESSION = [];
    session_destroy();
    session_start();
}

if (isset($_SESSION['_created_at']) && (time() - (int) $_SESSION['_created_at']) > 900) {
    session_regenerate_id(true);
    $_SESSION['_created_at'] = time();
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'self'; form-action 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline';");

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($uri === '/health') {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'status' => 'ok',
        'module' => 'back',
        'mode' => 'mvc',
        'time' => date(DATE_ATOM),
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

require __DIR__ . '/../helpers/url.php';
require __DIR__ . '/../core/Controlller.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../core/Session.php';
require __DIR__ . '/../core/Auth.php';
require __DIR__ . '/../core/Csrf.php';
require __DIR__ . '/../core/Validator.php';

$router = new Router();
require __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
