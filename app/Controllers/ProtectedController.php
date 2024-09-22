<?php
namespace App\Controllers;

use Core\JWTHandler;

class ProtectedController
{
    private $jwt;

    public function __construct()
    {
        $this->jwt = new JWTHandler();
    }

    // Protected route that requires a valid JWT token
    public function index()
    {
        // Get the JWT token from cookies
        $token = $_COOKIE['token'] ?? null;

        if ($token) {
            // Validate the token
            $decoded = $this->jwt->validateToken($token);

            if ($decoded) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Access granted',
                    'data' => $decoded
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid or expired token'
                ]);
            }
        } else {
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'JWT token missing in cookies'
            ]);
        }
    }
}
