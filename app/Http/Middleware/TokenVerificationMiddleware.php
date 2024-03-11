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

        $token = $request->header("token");

        // Verify the token using the JWTToken::VerifyToken method
        $result = JWTToken::VerifyToken($token);

        // Check if the token verification failed
        if ($result == "unauthorized") {
            // Return a JSON response with status "failed" and a 401 Unauthorized HTTP status code
            return response()->json([
                "status" => "failed",
                "message" => "Unauthorized"
            ], 401);
        } else {
            // If token verification succeeds, set the "email" header in the request with the result
            $request->headers->set("email", $result);
            // Continue processing the request by passing it to the next middleware or controller
            return $next($request);
        }
    }
}
