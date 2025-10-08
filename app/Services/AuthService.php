<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Http\Request;
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

            $user = auth()->user();
            $token = auth()->claims([
                'client_id' => $user->client_id,
                'access_level' => $user->access_level,
                'name' => $user->name
            ])->login($user);

            return [
                'success' => true,
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $this->geTokenExpires(),
                'user' => auth()->user()->name
            ];
        } catch (\Throwable $e) {
            Log::error([
                'errorMessage' =>  $e->getMessage(),
                'file' => $e->getFile(),
                'number' => $e->getLine()
            ]);
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