<?php
session_start();
require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../helpers/url.php';
require __DIR__ . '/../helpers/view.php';
require __DIR__ . '/../middlewares/AuthMiddleware.php';

$router = new Router();
$router->get('/', ['HomeController', 'index'], [], 'home');
$router->get('/login', ['AuthController', 'login'], [], 'login');
$router->get('/register', ['AuthController', 'register'], [], 'register');
$router->get('/dashboard', ['DashboardController', 'index'], ['AuthMiddleware'], 'dashboard');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
