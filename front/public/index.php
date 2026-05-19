<?php
session_start();
require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../middlewares/AuthMiddleware.php';

$router = new Router();
$router->get('/', ['HomeController', 'index']);
$router->get('/login', ['AuthController', 'login']);
$router->get('/register', ['AuthController', 'register']);
$router->get('/dashboard', ['DashboardController', 'index']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
