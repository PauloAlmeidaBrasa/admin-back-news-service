<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        try {
            $token = $this->authService->login($credentials);
    
            return $this->respondWithToken($token);
        } catch (\Throwable $th) {
            Log::debug([
                'Error' => $th->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Authentication service unavailable'
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        $this->authService->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => auth()->user()->name,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
