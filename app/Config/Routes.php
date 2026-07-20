<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Home::index');
$routes->get('/inscription', 'Home::inscription');

$routes->get('/base', 'ConnexionController::connexion');

$routes->get('/home', 'Home::home');

$routes->get('/stats', 'Home::stats');
