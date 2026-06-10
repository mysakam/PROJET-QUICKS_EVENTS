<?php

$router->get('/', ['DashBoard', 'index'], [], 'back_home');
$router->get('/admin-login', ['DashBoard', 'adminLogin'], [], 'admin_login');
$router->post('/admin-login', ['DashBoard', 'adminAuthenticate'], [], 'admin_login_post');
$router->get('/logout', ['DashBoard', 'logout'], ['AuthMiddleware'], 'logout');

$router->get('/dashboard', ['DashBoard', 'dashboard'], ['AuthMiddleware', 'AdminMiddleware'], 'dashboard');

$router->get('/users', ['UsersController', 'index'], ['AuthMiddleware', 'AdminMiddleware'], 'users_index');
$router->post('/users', ['UsersController', 'store'], ['AuthMiddleware', 'AdminMiddleware'], 'users_store');
$router->get('/users/create', ['UsersController', 'create'], ['AuthMiddleware', 'AdminMiddleware'], 'users_create');
$router->get('/users/{id}/edit', ['UsersController', 'edit'], ['AuthMiddleware', 'AdminMiddleware'], 'users_edit');
$router->post('/users/{id}', ['UsersController', 'update'], ['AuthMiddleware', 'AdminMiddleware'], 'users_update');
$router->post('/users/{id}/delete', ['UsersController', 'delete'], ['AuthMiddleware', 'AdminMiddleware'], 'users_delete');

$router->get('/categories', ['CategoriesController', 'index'], ['AuthMiddleware', 'AdminMiddleware'], 'categories_index');
$router->post('/categories', ['CategoriesController', 'store'], ['AuthMiddleware', 'AdminMiddleware'], 'categories_store');
$router->get('/categories/create', ['CategoriesController', 'create'], ['AuthMiddleware', 'AdminMiddleware'], 'categories_create');
$router->get('/categories/{id}/edit', ['CategoriesController', 'edit'], ['AuthMiddleware', 'AdminMiddleware'], 'categories_edit');
$router->post('/categories/{id}', ['CategoriesController', 'update'], ['AuthMiddleware', 'AdminMiddleware'], 'categories_update');
$router->post('/categories/{id}/delete', ['CategoriesController', 'delete'], ['AuthMiddleware', 'AdminMiddleware'], 'categories_delete');

$router->get('/prestations', ['PrestationsController', 'index'], ['AuthMiddleware', 'AdminMiddleware'], 'prestations_index');
$router->post('/prestations', ['PrestationsController', 'store'], ['AuthMiddleware', 'AdminMiddleware'], 'prestations_store');
$router->get('/prestations/create', ['PrestationsController', 'create'], ['AuthMiddleware', 'AdminMiddleware'], 'prestations_create');
$router->get('/prestations/{id}/edit', ['PrestationsController', 'edit'], ['AuthMiddleware', 'AdminMiddleware'], 'prestations_edit');
$router->post('/prestations/{id}', ['PrestationsController', 'update'], ['AuthMiddleware', 'AdminMiddleware'], 'prestations_update');
$router->post('/prestations/{id}/delete', ['PrestationsController', 'delete'], ['AuthMiddleware', 'AdminMiddleware'], 'prestations_delete');

$router->get('/devis', ['DevisController', 'index'], ['AuthMiddleware', 'AdminMiddleware'], 'devis_index');
$router->get('/devis/{id}', ['DevisController', 'show'], ['AuthMiddleware', 'AdminMiddleware'], 'devis_show');

$router->get('/media', ['MediaController', 'index'], ['AuthMiddleware', 'AdminMiddleware'], 'media_index');
$router->post('/media', ['MediaController', 'store'], ['AuthMiddleware', 'AdminMiddleware'], 'media_store');
$router->get('/media/create', ['MediaController', 'create'], ['AuthMiddleware', 'AdminMiddleware'], 'media_create');
$router->get('/media/{id}/edit', ['MediaController', 'edit'], ['AuthMiddleware', 'AdminMiddleware'], 'media_edit');
$router->post('/media/{id}', ['MediaController', 'update'], ['AuthMiddleware', 'AdminMiddleware'], 'media_update');
$router->post('/media/{id}/delete', ['MediaController', 'delete'], ['AuthMiddleware', 'AdminMiddleware'], 'media_delete');
