<?php

class Router
{
    private $routes = [];

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

        // Check if the requested route exists
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            http_response_code(404);
            echo "404 - Not Found";
            exit;
        }

        // If callback is an array (e.g., [HomeController::class, 'index']), call the controller's method
        if (is_array($callback)) {
            $controller = new $callback[0];  // Instantiate the controller
            $method = $callback[1];  // Get the method name

            if (!method_exists($controller, $method)) {
                http_response_code(500);
                echo "Method $method not found in controller " . get_class($controller);
                exit;
            }

            // Call the controller method with instantiated controller
            return call_user_func([$controller, $method]);
        }

        // Call closure/function directly
        return call_user_func($callback);
    }

    // Helper method to get the request path
    private function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?'); // To remove query string if present
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }
}
