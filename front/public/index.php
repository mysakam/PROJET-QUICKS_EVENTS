<?php
session_start();

require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../helpers/url.php';
require __DIR__ . '/../helpers/view.php';
require __DIR__ . '/../middlewares/AuthMiddleware.php';

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
$router->get('/devis', ['DevisController', 'checkout'], ['AuthMiddleware'], 'devis_checkout');
$router->post('/devis', ['DevisController', 'store'], ['AuthMiddleware'], 'devis_store');
$router->get('/devis/succes/{id}', ['DevisController', 'success'], ['AuthMiddleware'], 'devis_success');
$router->get('/mes-devis', ['DevisController', 'index'], ['AuthMiddleware'], 'devis_index');
$router->get('/mes-devis/{id}', ['DevisController', 'show'], ['AuthMiddleware'], 'devis_show');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);