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
require __DIR__ . '/../../shared/mail/mailer.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';

require __DIR__ . '/../models/CategoryModel.php';
require __DIR__ . '/../models/PrestationModel.php';
require __DIR__ . '/../models/DevisModel.php';
require __DIR__ . '/../models/DevisLigneModel.php';
require __DIR__ . '/../models/FactureModel.php';
require __DIR__ . '/../models/ClientModel.php';
require __DIR__ . '/../models/PrestataireModel.php';
require __DIR__ . '/../models/EventMediaModel.php';

require __DIR__ . '/../controllers/AdminBaseController.php';
require __DIR__ . '/../controllers/AdminDashboardController.php';
require __DIR__ . '/../controllers/AdminPrestatairesController.php';
require __DIR__ . '/../controllers/AdminFacturesController.php';
require __DIR__ . '/../controllers/AdminClientsController.php';
require __DIR__ . '/../controllers/AdminStatsController.php';

$router = new Router();

/* Pages */
$router->get('/', ['HomeController', 'index'], [], 'home');
$router->get('/cug', ['HomeController', 'cug'], [], 'cug');
$router->get('/dashboard', ['DashboardController', 'index'], ['AuthMiddleware'], 'dashboard');
$router->get('/mariage', ['EventPagesController', 'mariage'], [], 'event_mariage');
$router->get('/anniversaire', ['EventPagesController', 'anniversaire'], [], 'event_anniversaire');
$router->get('/soiree-theme', ['EventPagesController', 'soireeTheme'], [], 'event_soiree_theme');
$router->get('/repas-seminaire', ['EventPagesController', 'repasSeminaire'], [], 'event_repas_seminaire');
$router->get('/events/{slug}/packages/{index}/select', ['EventPagesController', 'selectPackage'], ['AuthMiddleware'], 'event_package_select');

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
$router->get('/mon-evenement', ['DevisController', 'eventRequest'], ['AuthMiddleware'], 'mon_evenement');
$router->post('/mon-evenement', ['DevisController', 'eventRequestStore'], ['AuthMiddleware'], 'mon_evenement_post');
$router->get('/devis/checkout', ['DevisController', 'checkout'], ['AuthMiddleware'], 'devis_checkout');
$router->post('/devis/store', ['DevisController', 'store'], ['AuthMiddleware'], 'devis_store');
$router->post('/devis/{id}/valider', ['DevisController', 'validate'], ['AuthMiddleware'], 'devis_validate');
$router->post('/devis/{id}/annuler', ['DevisController', 'cancel'], ['AuthMiddleware'], 'devis_cancel');
$router->post('/devis/{id}/reprendre', ['DevisController', 'reopen'], ['AuthMiddleware'], 'devis_reopen');
$router->get('/devis/success/{id}', ['DevisController', 'success'], ['AuthMiddleware'], 'devis_success');
$router->get('/devis', ['DevisController', 'index'], ['AuthMiddleware'], 'devis_index');
$router->get('/factures', ['DevisController', 'factures'], ['AuthMiddleware'], 'factures_index');
$router->get('/devis/{id}', ['DevisController', 'show'], ['AuthMiddleware'], 'devis_show');

/* Admin medias evenements */
$router->get('/admin', ['AdminDashboardController', 'index'], ['AuthMiddleware'], 'admin_dashboard');

$router->get('/admin/event-medias', ['EventMediaAdminController', 'index'], ['AuthMiddleware'], 'admin_event_medias');
$router->get('/admin/event-medias/create', ['EventMediaAdminController', 'create'], ['AuthMiddleware'], 'admin_event_medias_create');
$router->post('/admin/event-medias', ['EventMediaAdminController', 'store'], ['AuthMiddleware'], 'admin_event_medias_store');
$router->get('/admin/event-medias/{id}/edit', ['EventMediaAdminController', 'edit'], ['AuthMiddleware'], 'admin_event_medias_edit');
$router->post('/admin/event-medias/{id}/update', ['EventMediaAdminController', 'update'], ['AuthMiddleware'], 'admin_event_medias_update');
$router->post('/admin/event-medias/{id}/delete', ['EventMediaAdminController', 'delete'], ['AuthMiddleware'], 'admin_event_medias_delete');

$router->get('/admin/prestataires', ['AdminPrestatairesController', 'index'], ['AuthMiddleware'], 'admin_prestataires_index');
$router->get('/admin/prestataires/create', ['AdminPrestatairesController', 'create'], ['AuthMiddleware'], 'admin_prestataires_create');
$router->get('/admin/prestataires/{id}', ['AdminPrestatairesController', 'show'], ['AuthMiddleware'], 'admin_prestataires_show');
$router->post('/admin/prestataires', ['AdminPrestatairesController', 'store'], ['AuthMiddleware'], 'admin_prestataires_store');
$router->get('/admin/prestataires/{id}/edit', ['AdminPrestatairesController', 'edit'], ['AuthMiddleware'], 'admin_prestataires_edit');
$router->post('/admin/prestataires/{id}/update', ['AdminPrestatairesController', 'update'], ['AuthMiddleware'], 'admin_prestataires_update');
$router->post('/admin/prestataires/{id}/delete', ['AdminPrestatairesController', 'delete'], ['AuthMiddleware'], 'admin_prestataires_delete');

$router->get('/admin/factures', ['AdminFacturesController', 'index'], ['AuthMiddleware'], 'admin_factures_index');
$router->get('/admin/factures/create', ['AdminFacturesController', 'create'], ['AuthMiddleware'], 'admin_factures_create');
$router->post('/admin/factures', ['AdminFacturesController', 'store'], ['AuthMiddleware'], 'admin_factures_store');
$router->get('/admin/factures/{id}', ['AdminFacturesController', 'show'], ['AuthMiddleware'], 'admin_factures_show');
$router->get('/admin/factures/{id}/edit', ['AdminFacturesController', 'edit'], ['AuthMiddleware'], 'admin_factures_edit');
$router->post('/admin/factures/{id}/update', ['AdminFacturesController', 'update'], ['AuthMiddleware'], 'admin_factures_update');
$router->post('/admin/factures/{id}/delete', ['AdminFacturesController', 'delete'], ['AuthMiddleware'], 'admin_factures_delete');
$router->post('/admin/factures/{id}/send-mail', ['AdminFacturesController', 'sendMail'], ['AuthMiddleware'], 'admin_factures_send_mail');

$router->get('/admin/clients', ['AdminClientsController', 'index'], ['AuthMiddleware'], 'admin_clients_index');
$router->get('/admin/clients/create', ['AdminClientsController', 'create'], ['AuthMiddleware'], 'admin_clients_create');
$router->post('/admin/clients', ['AdminClientsController', 'store'], ['AuthMiddleware'], 'admin_clients_store');
$router->get('/admin/clients/{id}', ['AdminClientsController', 'show'], ['AuthMiddleware'], 'admin_clients_show');
$router->get('/admin/clients/{id}/edit', ['AdminClientsController', 'edit'], ['AuthMiddleware'], 'admin_clients_edit');
$router->post('/admin/clients/{id}/update', ['AdminClientsController', 'update'], ['AuthMiddleware'], 'admin_clients_update');
$router->post('/admin/clients/{id}/delete', ['AdminClientsController', 'delete'], ['AuthMiddleware'], 'admin_clients_delete');

$router->get('/admin/statistiques', ['AdminStatsController', 'index'], ['AuthMiddleware'], 'admin_stats_index');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
