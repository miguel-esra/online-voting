<?php

use App\Controllers\AdminController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->group('', static function($routes) {
    $routes->group('', ['filter' => 'cifilter:authVoter'], static function($routes) {
        $routes->get('votacion-virtual/inicio', 'VoterController::index', ['as' => 'user.home']);
        $routes->get('votacion-virtual/salir', 'VoterController::logoutHandler', ['as' => 'user.logout']);
        $routes->get('votacion-virtual/ajustes', 'VoterController::settings', ['as' => 'user.settings']);
        $routes->get('votacion-virtual/perfil', 'VoterController::profile', ['as' => 'user.profile']);
        $routes->post('votacion-virtual/registrar-voto', 'VoterController::addVote', ['as' => 'user.add-vote']);
        $routes->get('votacion-virtual/mi-voto', 'VoterController::myVote', ['as' => 'user.my.vote']);
    });
    
    $routes->group('', ['filter' => 'cifilter:authAdmin'], static function($routes) {
        $routes->get('votacion-virtual/administrador/inicio', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('votacion-virtual/administrador/salir', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('votacion-virtual/administrador/ajustes', 'AdminController::settings', ['as' => 'admin.settings']);
    });

    $routes->group('', ['filter' => 'cifilter:guestVoter'], static function($routes) {
        $routes->get('votacion-virtual', 'AuthController::loginUser', ['as' => 'user.login.form']);
        $routes->post('votacion-virtual', 'AuthController::loginHandlerUser', ['as' => 'user.login.handler']);
    });

    $routes->group('', ['filter' => 'cifilter:guestAdmin'], static function($routes) {
        $routes->get('votacion-virtual/administrador', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('votacion-virtual/administrador', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
    });
});
