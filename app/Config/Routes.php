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