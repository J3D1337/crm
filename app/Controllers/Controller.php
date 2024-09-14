<?php

namespace App\Controllers;

class Controller
{
    // Method to send a JSON response
    protected function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // Method to handle redirects
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    // Sanitize user input
    protected function sanitize($data)
    {
        return htmlspecialchars(strip_tags($data));
    }

    // Retrieve POST/GET data
    protected function getInput($key = null, $default = null)
    {
        $inputData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

        if ($key === null) {
            return $inputData;
        }

        return $inputData[$key] ?? $default;
    }

    // Check if request is POST
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    // Check if request is GET
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    // Render a view
    protected function render($view, $data = [])
    {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
