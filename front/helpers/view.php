<?php

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
function view(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);

    $viewPath = __DIR__ . '/../views/' . $view . '.php';

    if (!file_exists($viewPath)) {
        http_response_code(500);
        echo 'Vue introuvable : ' . $view;
        return;
    }

    require $viewPath;
}