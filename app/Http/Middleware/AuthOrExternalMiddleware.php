<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\ExternalApiTokenService;

class AuthOrExternalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // If the user is authenticated with Sanctum, allow access
         if (Auth::guard('sanctum')->check()) {
            return $next($request);
        }

        // If a valid X-Authorization token is passed, allow access
        $token = $request->header('X-Authorization');

        if ($token) {
            $tokenRecord = (new ExternalApiTokenService)->getByToken($token);

            if ($tokenRecord) {
                return $next($request);
            }
        }

        // If none of the methods are valid, return 401
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
