<?php

if (!defined('BASE_URL')) {
    define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $path = ltrim($path, '/');

        if (BASE_URL === '' || BASE_URL === '/') {
            return '/' . $path;
        }

        return BASE_URL . ($path ? '/' . $path : '');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return url($path);
    }
}

if (!function_exists('route')) {
    function route(string $name, array $params = []): string
    {
        if (!Router::$instance) {
            return '#';
        }

        return url(ltrim(Router::$instance->url($name, $params), '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $to): void
    {
        header('Location: ' . $to);
        exit;
    }
}