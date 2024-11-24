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
    
    $routes->group('', ['filter' => 'cifilter:guestVoter'], static function($routes) {
        $routes->get('votacion-virtual', 'AuthController::loginUser', ['as' => 'user.login.form']);
        $routes->post('votacion-virtual', 'AuthController::loginHandlerUser', ['as' => 'user.login.handler']);
    });
});

// $routes->group('admin', static function($routes){

//     $routes->group('', ['filter' => 'cifilter:auth'], static function($routes){
//         $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
//         $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
//         $routes->get('profile', 'AdminController::profile', ['as' => 'admin.profile']);
//         $routes->post('update-personal-details', 'AdminController::updatePersonalDetails', ['as' => 'update-personal-details']);
//         $routes->post('update-profile-picture', 'AdminController::updateProfilePicture', ['as' => 'update-profile-picture']);
//         $routes->post('change-password', 'AdminController::changePassword', ['as' => 'change-password']);
//         $routes->get('settings', 'AdminController::settings', ['as' => 'settings']);
//         $routes->post('update-general-settings', 'AdminController::updateGeneralSettings', ['as' => 'update-general-settings']);
//         $routes->post('update-blog-logo', 'AdminController::updateBlogLogo', ['as' => 'update-blog-logo']);
//         $routes->post('update-blog-favicon', 'AdminController::updateBlogFavicon', ['as' => 'update-blog-favicon']);
//         $routes->post('update-social-media', 'AdminController::updateSocialMedia', ['as' => 'update-social-media']);
//         $routes->get('categories', 'AdminController::categories', ['as' => 'categories']);
//         $routes->post('add-category', 'AdminController::addCategory', ['as' => 'add-category']);
//         $routes->get('get-categories', 'AdminController::getCategories', ['as' => 'get-categories']);
//         $routes->get('get-category', 'AdminController::getCategory', ['as' => 'get-category']);
//         $routes->post('update-category', 'AdminController::updateCategory', ['as' => 'update-category']);
//         $routes->get('delete-category', 'AdminController::deleteCategory', ['as' => 'delete-category']);
//         $routes->get('reorder-categories', 'AdminController::reorderCategories', ['as' => 'reorder-categories']);
//         $routes->get('get-parent-categories', 'AdminController::getParentCategories', ['as' => 'get-parent-categories']);
//         $routes->post('add-subcategory', 'AdminController::addSubcategory', ['as' => 'add-subcategory']);
//         $routes->get('get-subcategories', 'AdminController::getSubcategories', ['as' => 'get-subcategories']);
//     });

//     $routes->group('', ['filter' => 'cifilter:guest'], static function($routes){
//         $routes->get('login', 'AuthController::loginForm', ['as' => 'admin.login.form']);
//         $routes->post('login', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
//     });

// });
