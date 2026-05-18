<?php
session_start();

require __DIR__ . '/../core/Controller.php';
require __DIR__  .'/../controllers/HomeController.php';

$home = new HomeController();
$home->index();