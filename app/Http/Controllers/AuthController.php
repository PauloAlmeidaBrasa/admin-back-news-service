<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {

        try {
            $credentials = $request->only('email', 'password');


            $token = $this->authService->login($credentials);   
    
            if (!$token) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
    
            return response()->json(['token' => $token]);
        } catch (\Throwable $th) {
            dd($th);
        }
        // $credentials = $request->only('email', 'password');


        // $token = $this->authService->login($credentials);

        // if (!$token) {
        //     return response()->json(['message' => 'Invalid credentials'], 401);
        // }

        // return response()->json(['token' => $token]);
    }
    public function logout(Request $request)
    {
        $this->authService->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
