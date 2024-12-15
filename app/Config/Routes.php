<?php

use App\Controllers\AdminController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->group('', static function($routes) {
    $routes->group('', ['filter' => 'cifilter:authVoter'], static function($routes) {
        $routes->get('dev/elecciones-sindicato-2025-2026/inicio', 'VoterController::index', ['as' => 'user.home']);
        $routes->get('dev/elecciones-sindicato-2025-2026/salir', 'VoterController::logoutHandler', ['as' => 'user.logout']);
        $routes->get('dev/elecciones-sindicato-2025-2026/ajustes', 'VoterController::settings', ['as' => 'user.settings']);
        $routes->get('dev/elecciones-sindicato-2025-2026/perfil', 'VoterController::profile', ['as' => 'user.profile']);
        $routes->post('dev/elecciones-sindicato-2025-2026/registrar-voto', 'VoterController::addVote', ['as' => 'user.add-vote']);
        $routes->get('dev/elecciones-sindicato-2025-2026/mi-voto', 'VoterController::myVote', ['as' => 'user.my.vote']);
    });

    $routes->group('', ['filter' => 'cifilter:authAdmin'], static function($routes) {
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador/inicio', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador/salir', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador/ajustes', 'AdminController::settings', ['as' => 'admin.settings']);
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador/perfil', 'AdminController::profile', ['as' => 'admin.profile']);
        $routes->post('dev/elecciones-sindicato-2025-2026/administrador/actualizar-datos-personales', 'AdminController::updatePersonalDetails', ['as' => 'update-personal-details']);
        $routes->post('dev/elecciones-sindicato-2025-2026/administrador/actualizar-foto-perfil', 'AdminController::updateProfilePicture', ['as' => 'update-profile-picture']);
        $routes->post('dev/elecciones-sindicato-2025-2026/administrador/cambiar-clave', 'AdminController::changePassword', ['as' => 'change-password']);
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador/obtener-resultados', 'AdminController::getVotingResults', ['as' => 'get-results']);
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador/participantes', 'AdminController::participants', ['as' => 'admin.participants']);
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador/obtener-participantes', 'AdminController::getParticipants', ['as' => 'get-participants']);
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador/resultados-elecciones', 'AdminController::resultsPDF', ['as' => 'get-results-pdf']);
    });

    $routes->group('', ['filter' => 'cifilter:guestVoter'], static function($routes) {
        $routes->get('dev/elecciones-sindicato-2025-2026', 'AuthController::loginUser', ['as' => 'user.login.form']);
        $routes->post('dev/elecciones-sindicato-2025-2026', 'AuthController::loginHandlerUser', ['as' => 'user.login.handler']);
    });

    $routes->group('', ['filter' => 'cifilter:guestAdmin'], static function($routes) {
        $routes->get('dev/elecciones-sindicato-2025-2026/administrador', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('dev/elecciones-sindicato-2025-2026/administrador', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
    });

    $routes->group('', ['filter' => 'cifilter:maintenanceMode'], static function($routes) {
        $routes->get('dev/elecciones-sindicato-2025-2026/evento-finalizado', 'AuthController::maintenance', ['as' => 'user.maintenance']);
    });
});
