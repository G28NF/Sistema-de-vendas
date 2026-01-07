<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('usuarios', 'UsuarioController::index');

$routes->get('health/db', function () {
    try {
        $db = \Config\Database::connect();
        $db->query('SELECT 1');
        return 'DB OK';
    } catch (\Throwable $e) {
        return $e->getMessage();
    }
});

