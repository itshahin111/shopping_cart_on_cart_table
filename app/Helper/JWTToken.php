<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    // Function to create a JWT token for a given user email
    public static function CreateToken($userEmail): string
    {
        $key = env("JWT_KEY"); // Retrieve the JWT key from environment variables
        $payload = [
            "iss" => "laravel-token", // Issuer claim
            "iat" => time(), // Issued at claim (current time)
            "exp" => time() + (60 * 60), // Expiration time (current time + 1 hour)
            "userEmail" => $userEmail // Custom claim: user email
        ];

        // Encode the payload using the JWT library with the specified key and algorithm
        return JWT::encode($payload, $key, "HS256");
    }

    // Function to create a JWT token for setting a password with a shorter expiration time
    public static function CreateTokenForSetPassword($userEmail): string
    {
        $key = env('JWT_KEY'); // Retrieve the JWT key from environment variables
        $payload = [
            'iss' => 'laravel-token', // Issuer claim
            'iat' => time(), // Issued at claim (current time)
            'exp' => time() + 60 * 20, // Expiration time (current time + 20 minutes)
            'userEmail' => $userEmail // Custom claim: user email
        ];

        // Encode the payload using the JWT library with the specified key and algorithm
        return JWT::encode($payload, $key, 'HS256');
    }

    // Function to verify and decode a JWT token and retrieve the user email
    public static function VerifyToken($token): string
    {
        try {
            $key = env("JWT_KEY"); // Retrieve the JWT key from environment variables

            // Decode the token using the JWT library with the specified key and algorithm
            $decode = JWT::decode($token, new Key($key, 'HS256'));

            // Return the user email from the decoded token
            return $decode->userEmail;
        } catch (Exception $exception) {
            return 'unauthorized'; // Return 'unauthorized' instead of a string for an unauthorized token
        }
    }
}
