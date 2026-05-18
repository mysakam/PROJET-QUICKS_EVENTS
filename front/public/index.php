<?php
session_start();

require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Router.php';

$router = new Router();
$router->get('/', function () {
    require __DIR__ . '/../views/home/index.php';
});

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);