<?php
namespace Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = 'your-secret-key'; // Replace with your actual secret key
    }

    // Create JWT token
    public function createToken($data)
    {
        $payload = [
            'iss' => 'your-app-name', // Issuer
            'iat' => time(),           // Issued at time
            'exp' => time() + 3600,    // Expiration time (1 hour)
            'data' => $data            // User data
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    // Validate JWT token
    public function validateToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->secretKey, 'HS256'));
        } catch (\Exception $e) {
            return false; // Invalid token
        }
    }
}
