<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;


class CheckAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $requiredLevel): Response
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json(['error' => 'User not found CheckAccessLevel Middleware'], 401);
        }

        if ($user->access_level < $requiredLevel) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient access level'
            ], 403);
        }
        
        return $next($request);
    }
}
