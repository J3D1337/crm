<?php
require_once __DIR__ . '/../../core/JWTHandler.php';

class AuthController
{
    private $jwt;

    public function __construct()
    {
        $this->jwt = new JWTHandler();
    }

    // Simulate login and return JWT token
    public function login()
    {
        // Normally, you'd verify the username and password from the request
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // For testing, we'll assume the login is successful if username and password are not empty
        if (!empty($username) && !empty($password)) {
            // Assuming user ID is 123 for the logged-in user
            $userId = 123;
            $token = $this->jwt->generateToken($userId);

            echo json_encode([
                'status' => 'success',
                'token' => $token
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ]);
        }
    }
}
