<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ConnexionController::index');
$routes->post('connexion/login', 'ConnexionController::login');
$routes->post('connexion/loginAdmin', 'ConnexionController::loginAdmin');


$routes->get('home', 'ConnexionController::home');
$routes->post('client/depot', 'ConnexionController::depot');
$routes->post('client/retrait', 'ConnexionController::retrait');
$routes->post('client/transfert', 'ConnexionController::transfert');

$routes->get('admin/dashboard', 'AdminController::dashboard');
$routes->post('admin/prefixe/add', 'AdminController::addPrefixe');
$routes->get('admin/prefixe/toggle/(:num)', 'AdminController::togglePrefixe/$1');
$routes->post('admin/bareme/add', 'AdminController::addBareme');
$routes->get('admin/logout', 'AdminController::logout');