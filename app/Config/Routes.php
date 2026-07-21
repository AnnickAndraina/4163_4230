<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');
$routes->post('connexion/login', 'AuthController::login');
$routes->post('connexion/loginAdmin', 'AuthController::loginAdmin');
$routes->get('logout', 'AuthController::logout');

$routes->group('client', function ($routes) {
    $routes->get('home', 'ClientController::home');
    $routes->post('depot', 'DepotController::depot');
    $routes->post('retrait', 'RetraitController::retrait');
    $routes->post('transfert', 'TransfertController::transfert');
});

$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('prefixe/add', 'AdminController::addPrefixe');
    $routes->get('prefixe/toggle/(:num)', 'AdminController::togglePrefixe/$1');
    $routes->post('update-commission', 'AdminController::updateCommission');
    $routes->get('bareme/type/(:num)', 'AdminController::baremeType/$1');
    $routes->post('bareme/add', 'AdminController::addBareme');
    $routes->post('bareme/update', 'AdminController::updateBareme');
    $routes->get('gains/(:segment)', 'AdminController::gainsDetails/$1');
    $routes->get('logout', 'AdminController::logout');
});