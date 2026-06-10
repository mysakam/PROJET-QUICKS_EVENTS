<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: no-referrer-when-downgrade');

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
