<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

    //==============================================================
    // Rotas de Clientes - Acesso para 'user' E/OU 'admin'
    //==============================================================

    $routes->group('clientes', ['filter' => 'app_group:admin,user'], static function ($routes) {
        $routes->get('', 'ClienteController::index');
        $routes->post('criar', 'ClienteController::criar');
        $routes->post('login', 'ClienteController::login');
        $routes->post('editar', 'ClienteController::editar');
        $routes->get('delete', 'ClienteController::delete');
        $routes->post('listar', 'ClienteController::listar');
    });

    //==============================================================
    // Rotas de Clientes - Acesso para E 'admin'
    //==============================================================

    $routes->group('clientes', ['filter' => 'app_group:admin'], static function ($routes) {
        $routes->post('adminDelete', 'ClienteController::adminDelete');
    });