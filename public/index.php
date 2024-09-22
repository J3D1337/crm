<?php

// Prevent browser caching globally
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');

require_once __DIR__ . '/../vendor/autoload.php';  // Correct path to autoload

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use Core\JWTHandler;
use Core\Router;

// Load database configuration from config.php
$config = require __DIR__ . '/../config/config.php';
$dbConfig = $config['database'];

try {
    // Create a new PDO instance using the config values
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']};port={$dbConfig['port']}";
    $db = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Handle JWT extraction and validation
$token = $_COOKIE['token'] ?? null;
$jwtUser = null;

if ($token) {
    $jwtHandler = new JWTHandler();
    $decodedToken = $jwtHandler->validateToken($token);

    if ($decodedToken) {
        // JWT is valid, make user data globally available
        $jwtUser = (array) $decodedToken->data;
    }
}

// Define public routes that don't require authentication
$publicRoutes = ['/', '/login', '/register'];

// If the user is not logged in and the current route is not public, redirect to login
if (!$jwtUser && !in_array($_SERVER['REQUEST_URI'], $publicRoutes)) {
    header('Location: /login');
    exit;
}

// Initialize the Router
$router = new Router($db);

// Define routes for authentication
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/create', [ProductController::class, 'create']);
$router->post('/products/store', [ProductController::class, 'store']);
$router->get('/products/edit/{id}', [ProductController::class, 'edit']);
$router->post('/products/update/{id}', [ProductController::class, 'update']);
$router->get('/products/delete/{id}', [ProductController::class, 'delete']);

$router->get('/', function() use ($jwtUser) {
    // Correct path to include the home view file
    require_once __DIR__ . '/../app/Views/home.php';
});

// Resolve incoming requests
$router->resolve();
