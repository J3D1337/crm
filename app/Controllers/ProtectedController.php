<?php
// require_once __DIR__ . '/../../core/JWTHandler.php';
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
        // Get the Authorization header (Bearer Token)
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? '';

        // Check if a Bearer token is present
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];

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
                'message' => 'Authorization header missing'
            ]);
        }
    }
}
