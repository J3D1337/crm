<?php

namespace Core;

class Router
{
    private $routes = [];
    private $db;  // Add a property to store the PDO instance

    public function __construct($db)
    {
        $this->db = $db;  // Store the PDO instance
    }

    // Register a GET route
    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    // Register a POST route
    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    // Resolve the route based on the request
    public function resolve()
{
    $path = $this->getPath();
    $method = $_SERVER['REQUEST_METHOD'];

    // Match the current route
    $callback = $this->match($method, $path);

    if (!$callback) {
        http_response_code(404);
        echo "404 - Not Found";
        exit;
    }

    // If the callback is an array, it's a controller and method
    if (is_array($callback['callback'])) {
        return $this->executeController($callback['callback'], $callback['params']);
    }

    // If the callback is a Closure or function, just call it
    if (is_callable($callback['callback'])) {
        return call_user_func($callback['callback']);
    }

    // If it doesn't match either, return a 500 error
    http_response_code(500);
    echo "500 - Invalid callback";
    exit;
}

    // Helper method to get the request path
    private function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?'); // Remove query string
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    // Method to execute the controller's method with parameters
    private function executeController($callback, $params = [])
    {
        [$controllerClass, $method] = $callback;

        // Instantiate the controller and pass the $db instance
        $controller = new $controllerClass($this->db);

        if (!method_exists($controller, $method)) {
            echo "Method $method not found in controller " . get_class($controller);
            exit;
        }

        return call_user_func_array([$controller, $method], $params);
    }

    // Match routes with dynamic parameters (e.g., /products/edit/{id})
    private function match($method, $path)
    {
        foreach ($this->routes[$method] as $route => $callback) {
            // Convert route to a regex pattern by replacing {id} with (\d+)
            $routePattern = preg_replace('/\{(\w+)\}/', '(\d+)', $route);
            
            // Check if the path matches the route pattern
            if (preg_match("#^$routePattern$#", $path, $matches)) {
                array_shift($matches); // Remove the full match from the array
                
                // Return the callback and the dynamic parameters
                return ['callback' => $callback, 'params' => $matches];
            }
        }

        return false;
    }
}
