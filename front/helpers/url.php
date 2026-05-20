<?php
function route(string $name, array $params = []): string
{
    return Router::$instance ? Router::$instance->url($name, $params) : '/';
}

function asset(string $path): string
{
    return '/front/assets/' . ltrim($path, '/');
}