<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    // Function to create a JWT token for regular user authentication
    public static function CreateToken($userEmail, $userId): string
    {
        $key = env("JWT_KEY"); // Retrieve the JWT key from environment variables

        // Define the payload data for the JWT token
        $payload = [
            "iss" => "laravel-token", // Issuer claim
            "iat" => time(), // Issued at claim (current time)
            "exp" => time() + (60 * 60), // Expiration time (current time + 1 hour)
            "userEmail" => $userEmail, // Custom claim: user email
            "userId" => $userId // Custom claim: user ID
        ];

        // Encode the payload using the JWT library with the specified key and algorithm
        return JWT::encode($payload, $key, "HS256"); // Return the generated JWT token
    }

    // Function to create a JWT token with a shorter expiration time, typically for password reset
    public static function CreateTokenForSetPassword($userEmail): string
    {
        $key = env('JWT_KEY'); // Retrieve the JWT key from environment variables

        // Define the payload data for the JWT token
        $payload = [
            'iss' => 'laravel-token', // Issuer claim
            'iat' => time(), // Issued at claim (current time)
            'exp' => time() + 60 * 20, // Expiration time (current time + 20 minutes)
            'userEmail' => $userEmail, // Custom claim: user email
            "userId" => 0 // Placeholder for user ID, as this token is not associated with a specific user
        ];

        // Encode the payload using the JWT library with the specified key and algorithm
        return JWT::encode($payload, $key, "HS256"); // Return the generated JWT token
    }

    // Function to verify and decode a JWT token and retrieve the user email
    public static function VerifyToken($token): string|object
    {
        try {
            if ($token == null) {
                return 'unauthorized';
            } else {
                $key = env('JWT_KEY');
                $decode = JWT::decode($token, new Key($key, 'HS256'));
                return $decode;
            }
        } catch (Exception $exception) {
            return 'unauthorized'; // Return 'unauthorized' instead of a string for an unauthorized token
        }
    }
}
