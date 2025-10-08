<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;


class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {

        // dd($request);
        $credentials = $request->only('email', 'password');

        try {
            $token = $this->authService->login($credentials);

            if (!$token['success']) {
                return response()->json([
                    'message' => $token['message'],
                    'error' => $token['error']
                ], $token['status_code']);
            }
    
            return $this->respondWithToken($token);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Authentication service unavailable'
            ], 500);
        }
    }
}
