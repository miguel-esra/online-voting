<?php

use App\Controllers\AdminController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->group('', static function($routes) {
    $routes->group('', ['filter' => 'cifilter:authVoter'], static function($routes) {
        $routes->get('elecciones/votante/inicio', 'VoterController::index', ['as' => 'user.home']);
        $routes->get('elecciones/votante/salir', 'VoterController::logoutHandler', ['as' => 'user.logout']);
        $routes->get('elecciones/votante/ajustes', 'VoterController::settings', ['as' => 'user.settings']);
        $routes->get('elecciones/votante/perfil', 'VoterController::profile', ['as' => 'user.profile']);
        $routes->post('elecciones/votante/registrar-voto', 'VoterController::addVote', ['as' => 'user.add-vote']);
        $routes->get('elecciones/votante/mi-voto', 'VoterController::myVote', ['as' => 'user.my.vote']);
    });

    $routes->group('', ['filter' => 'cifilter:authAdmin'], static function($routes) {
        $routes->get('elecciones/administrador/inicio', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('elecciones/administrador/salir', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('elecciones/administrador/ajustes', 'AdminController::settings', ['as' => 'admin.settings']);
        $routes->get('elecciones/administrador/perfil', 'AdminController::profile', ['as' => 'admin.profile']);
        $routes->post('elecciones/administrador/actualizar-datos-personales', 'AdminController::updatePersonalDetails', ['as' => 'update-personal-details']);
        $routes->post('elecciones/administrador/actualizar-foto-perfil', 'AdminController::updateProfilePicture', ['as' => 'update-profile-picture']);
        $routes->post('elecciones/administrador/cambiar-clave', 'AdminController::changePassword', ['as' => 'change-password']);
        $routes->get('elecciones/administrador/obtener-resultados', 'AdminController::getVotingResults', ['as' => 'get-results']);
        $routes->get('elecciones/administrador/participantes', 'AdminController::participants', ['as' => 'admin.participants']);
        $routes->get('elecciones/administrador/obtener-participantes', 'AdminController::getParticipants', ['as' => 'get-participants']);
        $routes->get('elecciones/administrador/resultados-elecciones', 'AdminController::resultsPDF', ['as' => 'get-results-pdf']);
    });

    $routes->group('', ['filter' => 'cifilter:guestVoter'], static function($routes) {
        $routes->get('elecciones', 'AuthController::loginUser', ['as' => 'user.login.form']);
        $routes->post('elecciones', 'AuthController::loginHandlerUser', ['as' => 'user.login.handler']);
        $routes->get('elecciones/clave-olvidada', 'AuthController::forgotForm', ['as' => 'user.forgot.form']);
        $routes->post('elecciones/enlace-restablecer-clave', 'AuthController::sendPasswordResetLink', ['as' => 'send_password_reset_link']);
        $routes->get('elecciones/clave/restablecer/(:any)', 'AuthController::resetPassword/$1', ['as' => 'user.reset-password']);
        $routes->post('elecciones/gestor-restablecimiento-clave/(:any)', 'AuthController::resetPasswordHandler/$1', ['as' => 'reset-password-handler']);
    });

    $routes->group('', ['filter' => 'cifilter:guestAdmin'], static function($routes) {
        $routes->get('elecciones/administrador', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('elecciones/administrador', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
    });

    $routes->group('', ['filter' => 'cifilter:maintenanceMode'], static function($routes) {
        $routes->get('elecciones/evento-finalizado', 'AuthController::maintenance', ['as' => 'user.maintenance']);
    });
});
