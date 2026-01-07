<?php

namespace Config;

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// $routes->setAutoRoute(true); // en TD : on évite

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
$routes->get('/', 'HomeController::index');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
$routes->get('/login', 'AuthController::loginForm');
$routes->post('/login', 'AuthController::login');

$routes->get('/register', 'AuthController::registerForm');
$routes->post('/register', 'AuthController::register');

$routes->get('/logout', 'AuthController::logout');

/*Page des artistes*/
$routes->get('/artists', 'ArtistController::index');

/*
|--------------------------------------------------------------------------
| Beats / Boutique (public)
|--------------------------------------------------------------------------
*/
$routes->get('/beats', 'BeatController::index');
$routes->get('/boutique', 'BeatController::index'); // alias
$routes->get('/listings', 'BeatController::index'); // alias legacy

$routes->get('/search', 'BeatController::search');        // alias
$routes->get('/beats/search', 'BeatController::search');  // alias optionnel

// IMPORTANT : routes spécifiques avant /beats/(:num)
$routes->get('/beats/create', 'BeatController::createForm', ['filter' => 'auth']);
$routes->post('/beats/create', 'BeatController::create', ['filter' => 'auth']);

// legacy create
$routes->get('/listings/create', 'BeatController::createForm', ['filter' => 'auth']);
$routes->post('/listings/create', 'BeatController::create', ['filter' => 'auth']);

// show
$routes->get('/beats/(:num)', 'BeatController::show/$1');
$routes->get('/listings/(:num)', 'BeatController::show/$1'); // legacy

// download WAV après achat (protégé)
$routes->get('/beats/(:num)/download', 'BeatController::download/$1', ['filter' => 'auth']);

/*
|--------------------------------------------------------------------------
| Mon compte (protégé)
|--------------------------------------------------------------------------
*/
$routes->group('account', ['filter' => 'auth'], static function (RouteCollection $routes) {
    $routes->get('/', 'AccountController::index');

    $routes->get('profile', 'AccountController::profile');
    $routes->post('profile', 'AccountController::updateProfile');

    $routes->get('favorites', 'AccountController::favorites');
    $routes->get('conversations', 'AccountController::conversations');

    // beats depuis mon compte
    $routes->get('beats', 'AccountController::beatsIndex');
    $routes->get('beats/new', 'AccountController::beatCreateForm');
    $routes->post('beats', 'AccountController::beatCreate');
    $routes->get('beats/(:num)/edit', 'AccountController::beatEditForm/$1');
    $routes->post('beats/(:num)', 'AccountController::beatUpdate/$1');
    $routes->post('beats/(:num)/delete', 'AccountController::beatDelete/$1');

    // wallet/subscription/moderation
    $routes->get('wallet', 'AccountController::wallet');

    $routes->get('subscription', 'AccountController::subscription');
    $routes->post('subscription/buy', 'AccountController::buySubscription'); // simulation achat

    $routes->get('moderation', 'AccountController::moderation');
});

// alias FR
$routes->get('/mon-compte', 'AccountController::index', ['filter' => 'auth']);

/*
|--------------------------------------------------------------------------
| Favoris / Conversations / Messages (protégé)
|--------------------------------------------------------------------------
*/
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {

    // Mes beats
    $routes->get('/my/beats', 'BeatController::myBeats');
    $routes->get('/my/listings', 'BeatController::myBeats'); // legacy

    // Favoris
    $routes->get('/favorites', 'FavoriteController::index');
    $routes->post('/favorites/(:num)/toggle', 'FavoriteController::toggle/$1');
    $routes->post('/favorites/(:num)/add', 'FavoriteController::add/$1');
    $routes->post('/favorites/(:num)/remove', 'FavoriteController::remove/$1');

    // Conversations
    $routes->get('/conversations', 'ConversationsController::index');
    $routes->get('/conversations/(:num)', 'ConversationsController::show/$1');
    $routes->post('/conversations/start/(:num)', 'ConversationsController::start/$1');

    // Messages
    $routes->post('/conversations/(:num)/message', 'MessageController::send/$1');
});

/*
|--------------------------------------------------------------------------
| Cart (public: invité + connecté)
|--------------------------------------------------------------------------
| (actuellement tu utilises connecté uniquement, mais routes OK)
*/
$routes->get('/cart', 'CartController::show');
$routes->get('/panier', 'CartController::show');

$routes->post('/cart/add/(:num)', 'CartController::add/$1');
$routes->post('/cart/remove/(:num)', 'CartController::remove/$1');
$routes->post('/cart/remove-line/(:num)', 'CartController::removeLine/$1');

$routes->get('/cart/checkout', 'CartController::checkoutForm');
$routes->post('/cart/checkout', 'CartController::checkout');
