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
        $routes = [
            'home' => '/',
            'login' => '/login',
            'register' => '/register',
            'dashboard' => '/dashboard',

            'catalogues' => '/catalogues',
            'catalogues_category' => '/catalogues/{slug}',
            'prestations_show' => '/prestations/{id}',

            'panier' => '/panier',
            'panier_add' => '/panier/ajouter/{id}',
            'panier_remove' => '/panier/supprimer/{id}',
            'panier_clear' => '/panier/vider',

            'devis_checkout' => '/devis',
            'devis_store' => '/devis',
            'devis_success' => '/devis/succes/{id}',
            'devis_index' => '/mes-devis',
            'devis_show' => '/mes-devis/{id}',
        ];

        if (!isset($routes[$name])) {
            return '#';
        }

        $uri = $routes[$name];

        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', urlencode((string) $value), $uri);
        }

        return url($uri);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $to): void
    {
        header('Location: ' . $to);
        exit;
    }
}
