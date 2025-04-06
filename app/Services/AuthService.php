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
                Log::warning('Failed login attempt for: ' . ($credentials['email'] ?? 'unknown'));
                return false;
            }
            return $token;
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
}




/* namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        // Logic to register a user
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function login(array $credentials): ?string
    {
        // Logic to authenticate a user
        if (auth()->attempt($credentials)) {
            return auth()->user()->createToken('authToken')->plainTextToken;
        }

        return null;
    }

    public function logout(): void
    {
        // Logic to log out a user
        auth()->user()->tokens()->delete();
    }
} */
