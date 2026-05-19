<?php
function route($name)
{
    $routes = [
        'home' => '/',
        'login' => '/login',
        'register' => '/register',
        'dashboard' => '/dashboard',
    ];
    return $routes[$name] ?? '/';
}
