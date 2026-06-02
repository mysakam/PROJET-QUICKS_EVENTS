<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../helpers/url.php';
require __DIR__ . '/../helpers/view.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';

require __DIR__ . '/../models/CategoryModel.php';
require __DIR__ . '/../models/PrestationModel.php';
require __DIR__ . '/../models/DevisModel.php';
require __DIR__ . '/../models/DevisLigneModel.php';
require __DIR__ . '/../models/ClientModel.php';

$router = new Router();

/* Pages */
$router->get('/', ['HomeController', 'index'], [], 'home');
$router->get('/dashboard', ['DashboardController', 'index'], ['AuthMiddleware'], 'dashboard');
$router->get('/mariage', ['EventPagesController', 'mariage'], [], 'event_mariage');
$router->get('/anniversaire', ['EventPagesController', 'anniversaire'], [], 'event_anniversaire');
$router->get('/soiree-theme', ['EventPagesController', 'soireeTheme'], [], 'event_soiree_theme');
$router->get('/repas-seminaire', ['EventPagesController', 'repasSeminaire'], [], 'event_repas_seminaire');

/* Auth */
$router->get('/login', ['AuthController', 'login'], [], 'login');
$router->post('/login', ['AuthController', 'authenticate'], [], 'login_post');
$router->get('/register', ['AuthController', 'register'], [], 'register');
$router->post('/register', ['AuthController', 'store'], [], 'register_post');
$router->get('/logout', ['AuthController', 'logout'], [], 'logout');
$router->get('/mon-compte', ['AuthController', 'account'], ['AuthMiddleware'], 'account');

/* Catalogue */
$router->get('/catalogues', ['CatalogueController', 'index'], [], 'catalogues');
$router->get('/catalogues/{slug}', ['CatalogueController', 'showCategory'], [], 'catalogues_category');
$router->get('/prestations/{id}', ['CatalogueController', 'showPrestation'], [], 'prestations_show');

/* Panier */
$router->get('/panier', ['PanierController', 'index'], ['AuthMiddleware'], 'panier');
$router->post('/panier/ajouter/{id}', ['PanierController', 'add'], ['AuthMiddleware'], 'panier_add');
$router->post('/panier/supprimer/{id}', ['PanierController', 'remove'], ['AuthMiddleware'], 'panier_remove');
$router->post('/panier/vider', ['PanierController', 'clear'], ['AuthMiddleware'], 'panier_clear');

/* Devis */
$router->get('/devis/checkout', ['DevisController', 'checkout'], ['AuthMiddleware'], 'devis_checkout');
$router->post('/devis/store', ['DevisController', 'store'], ['AuthMiddleware'], 'devis_store');
$router->get('/devis/success/{id}', ['DevisController', 'success'], ['AuthMiddleware'], 'devis_success');
$router->get('/devis', ['DevisController', 'index'], ['AuthMiddleware'], 'devis_index');
$router->get('/devis/{id}', ['DevisController', 'show'], ['AuthMiddleware'], 'devis_show');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
