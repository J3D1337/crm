<?php
namespace Core;

use \Firebase\JWT\JWT;  // Use Firebase JWT library (make sure you have it installed via Composer)
use Exception;


class JWTHandler
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = getenv('JWT_SECRET_KEY') ?: 'your_secret_key';  // Get secret key from .env or fallback to a default value
    }

    // Generate a JWT token
    public function generateToken($userId)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // Token is valid for 1 hour

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'userId' => $userId
        ];

        // Encode the payload to generate the token
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    // Validate and decode a JWT token
    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, $this->secretKey, ['HS256']);
            return (array) $decoded;  // Return the decoded token as an array
        } catch (Exception $e) {
            return false;  // Token is invalid
        }
    }
}
