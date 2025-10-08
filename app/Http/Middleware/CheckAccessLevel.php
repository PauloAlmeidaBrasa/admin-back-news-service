<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $requiredLevel): Response
    {

        if (auth()->user()->access_level < $requiredLevel) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient access level'
            ], 403);
        }
        
        return $next($request);
    }
}
