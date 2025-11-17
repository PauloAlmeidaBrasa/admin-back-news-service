<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;




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

            $refreshToken = JWTAuth::fromUser($user);

            $payload = JWTAuth::setToken($token)->getPayload();
            $expiresIn = Carbon::createFromTimestamp($payload->get('exp'))->format('Y-m-d H:i:s');

            return [
                'success' => true,
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => auth()->user()->name,
                'expires_in' => $expiresIn,
                'refresh_token' => $refreshToken
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
        $objToken = response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);     
        return $objToken->getData()->authorisation->token;
    }
}