<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../helpers/url.php';
require __DIR__ . '/../helpers/view.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require __DIR__ . '/../core/Database.php';

require __DIR__ . '/../models/CategoryModel.php';
require __DIR__ . '/../models/PrestationModel.php';
require __DIR__ . '/../models/DevisModel.php';
require __DIR__ . '/../models/DevisLigneModel.php';

$router = new Router();

/* Pages déjà existantes */
$router->get('/', ['HomeController', 'index'], [], 'home');
$router->get('/login', ['AuthController', 'login'], [], 'login');
$router->get('/register', ['AuthController', 'register'], [], 'register');
$router->get('/dashboard', ['DashboardController', 'index'], ['AuthMiddleware'], 'dashboard');

/* Catalogue */
$router->get('/catalogues', ['CatalogueController', 'index'], [], 'catalogues');
$router->get('/catalogues/{slug}', ['CatalogueController', 'showCategory'], [], 'catalogues_category');
$router->get('/prestations/{id}', ['CatalogueController', 'showPrestation'], [], 'prestations_show');

/* Panier */
$router->get('/panier', ['PanierController', 'index'], [], 'panier');
$router->post('/panier/ajouter/{id}', ['PanierController', 'add'], [], 'panier_add');
$router->post('/panier/supprimer/{id}', ['PanierController', 'remove'], [], 'panier_remove');
$router->post('/panier/vider', ['PanierController', 'clear'], [], 'panier_clear');

/* Devis */
$router->get('/devis/checkout', ['DevisController', 'checkout'], [], 'devis_checkout');
$router->post('/devis/store', ['DevisController', 'store'], [], 'devis_store');
$router->get('/devis/success/{id}', ['DevisController', 'success'], [], 'devis_success');
$router->get('/devis', ['DevisController', 'index'], [], 'devis_index');
$router->get('/devis/{id}', ['DevisController', 'show'], [], 'devis_show');

$router->get('/login', ['AuthController', 'login'], [], 'login');
$router->post('/login', ['AuthController', 'authenticate'], [], 'login_post');

$router->get('/register', ['AuthController', 'register'], [], 'register');
$router->post('/register', ['AuthController', 'store'], [], 'register_post');

$router->get('/logout', ['AuthController', 'logout'], [], 'logout');

$router->get('/mon-compte', ['AuthController', 'account'], ['AuthMiddleware'], 'account');

$router->get('/devis/checkout', ['DevisController', 'checkout'], ['AuthMiddleware'], 'devis_checkout');
$router->post('/devis/store', ['DevisController', 'store'], ['AuthMiddleware'], 'devis_store');
$router->get('/devis', ['DevisController', 'index'], ['AuthMiddleware'], 'devis_index');
$router->get('/devis/{id}', ['DevisController', 'show'], ['AuthMiddleware'], 'devis_show');

$router->get('/login', ['AuthController', 'login'], [], 'login');
$router->post('/login', ['AuthController', 'authenticate'], [], 'login_post');

$router->get('/register', ['AuthController', 'register'], [], 'register');
$router->post('/register', ['AuthController', 'store'], [], 'register_post');

$router->get('/logout', ['AuthController', 'logout'], [], 'logout');

$router->get('/mon-compte', ['AuthController', 'account'], ['AuthMiddleware'], 'account');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);