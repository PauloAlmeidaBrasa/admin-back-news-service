<?php
namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;


class AuthService
{
    public function login($credentials)
    {

        if (!$token = JWTAuth::attempt($credentials)) {
            return $request->errorResponse('Invalid credentials', 401);
        }

        return $this->respondWithToken($request, $token);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function getAuthenticatedUser()
    {
        return Auth::user();
    }
        /**
     * Refresh token
     */
    public function refresh(LoginRequest $request)
    {
        return $this->respondWithToken(
            $request, 
            auth()->refresh()
        );
    }

    /**
     * Format token response
     */
    protected function respondWithToken(LoginRequest $request, $token)
    {
        return $request->successResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], 'Login successful');
    }
}