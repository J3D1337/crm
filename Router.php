<?php
class Router {
    private $routes = [];

    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['PATH_INFO'] ?? '/';
        
        if (isset($this->routes[$method][$path])) {
            list($controller, $action) = explode('@', $this->routes[$method][$path]);
            require_once "../app/Controllers/$controller.php";
            $controller = new $controller;
            $controller->$action();
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Route not found']);
        }
    }
}
?>
