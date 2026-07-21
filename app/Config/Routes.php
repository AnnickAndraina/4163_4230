<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');
$routes->post('connexion/login', 'AuthController::login');
$routes->post('connexion/loginAdmin', 'AuthController::loginAdmin');
$routes->get('logout', 'AuthController::logout');

$routes->get('client/home', 'ClientController::home');

$routes->post('client/depot', 'DepotController::depot');

$routes->post('client/retrait', 'RetraitController::retrait');

$routes->post('client/transfert', 'TransfertController::transfert');

$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->get('admin/prefixe/add', 'AdminController::addPrefixe');
$routes->get('admin/prefixe/toggle/(:num)', 'AdminController::togglePrefixe/$1');
$routes->post('admin/update-commission', 'AdminController::updateCommission');
$routes->get('admin/bareme/type/(:num)', 'AdminController::baremeType/$1');
$routes->post('admin/bareme/add', 'AdminController::addBareme');
$routes->post('admin/bareme/update', 'AdminController::updateBareme');
$routes->get('admin/gains/(:segment)', 'AdminController::gainsDetails/$1');
$routes->get('admin/logout', 'AdminController::logout');