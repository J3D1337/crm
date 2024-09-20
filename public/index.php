<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
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

$router = new Router($db);  // Pass the $db object to the Router

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

$router->get('/', [HomeController::class, 'index']);

// Resolve incoming requests
$router->resolve();
