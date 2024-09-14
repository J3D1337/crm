<?php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/controllers/Controller.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ProtectedController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

use App\Controllers\HomeController;


$router = new Router();

// Define routes
// $router->get('/', function () {
//     echo 'Welcome to the home page!';
// });

$router->get('/', [HomeController::class, 'index']);

// Authentication routes
$router->post('/login', 'AuthController@login');

// Protected route
$router->get('/protected', 'ProtectedController@index');

// Resolve incoming requests
$router->resolve();