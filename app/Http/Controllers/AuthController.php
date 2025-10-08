<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    protected $authService;

    public function __construct()
    {
        dd(__LINE__);
        // $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {

        dd('SSSSSSSSSS');
        $credentials = $request->only('email', 'password');

        try {
            // $token = $this->authService->login($credentials);

            // if (!$token['success']) {
            //     return response()->json([
            //         'message' => $token['message'],
            //         'error' => $token['error']
            //     ], $token['status_code']);
            // }
    
            return $this->respondWithToken('');
        } catch (\Throwable $th) {

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
            'access_token' => $token['token'],
            'token_type' => $token['token_type'],
            'expires_in' => $token['expires_in'],
            'user' => $token['user']
        ]);
    }
}


