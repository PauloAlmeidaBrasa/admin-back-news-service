<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;




class AuthService
{

    public function login(array $credentials)
    {

        try {
            if (!$token = JWTAuth::attempt($credentials)) {

                return [
                    'success' => false,
                    'error' => 'invalid_credentials',
                    'message' => 'Email or password is incorrect',
                    'status_code' => 401
                ];
            }

            return [
                'success' => true,
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $this->geTokenExpires(),
                'user' => auth()->user()->name
            ];
        } catch (JWTException $e) {
            Log::warning('Failed login attempt for: ' . ($credentials['email'] ?? 'unknown'));
            return false;
            
        } catch (\Throwable $e) {
            Log::error('Unexpected login error: ' . $e->getMessage());
            return false;
        }
    }

    public function register(Request $request)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    protected function geTokenExpires() {
        return auth()->factory()->getTTL() * 60;
    }
}