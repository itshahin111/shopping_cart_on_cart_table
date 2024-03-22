<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve the JWT token from the request cookie named "token"
        $token = $request->cookie("token");

        // Verify the token using the JWTToken::VerifyToken method
        $result = JWTToken::VerifyToken($token);

        // Check if the token verification failed
        if ($result == "unauthorized") {
            // If verification fails, redirect the user to the login page
            return redirect('/userLogin');
        } else {
            // If token verification succeeds, set the "email" header in the request with the user's email
            // and set the "id" header with the user's ID
            $request->headers->set("email", $result->userEmail);
            $request->headers->set("id", $result->userId);
            // Continue processing the request by passing it to the next middleware or controller
            return $next($request);
        }
    }
}
