<?php
namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials)
    {
        dd("SERVICE");
        if (!$token = JWTAuth::attempt($credentials)) {
            return null; // Return null if login fails
        }

        return $token;
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function getAuthenticatedUser()
    {
        return Auth::user();
    }
}