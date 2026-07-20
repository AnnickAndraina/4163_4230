<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ConnexionController::index');
$routes->post('connexion/login', 'ConnexionController::login');

$routes->post('connexion/loginAdmin', 'ConnexionController::loginAdmin');