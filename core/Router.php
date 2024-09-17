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

        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            http_response_code(404);
            echo "404 - Not Found";
            exit;
        }

        // If the callback is a controller@method
        if (is_array($callback)) {
            return $this->executeController($callback);
        }

        // If the callback is a Closure or function
        return call_user_func($callback);
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

    // Method to execute the controller's method
    private function executeController($callback)
    {
        [$controllerClass, $method] = $callback;

        // Instantiate the controller and pass the $db instance
        $controller = new $controllerClass($this->db);

        if (!method_exists($controller, $method)) {
            echo "Method $method not found in controller " . get_class($controller);
            exit;
        }

        return call_user_func([$controller, $method]);
    }
}
