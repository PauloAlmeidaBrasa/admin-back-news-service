<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Controllers\Api\V1\BaseController;



class AuthController extends BaseController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

/**
* 
* @OA\Post(
*     path="/api/v1/login",
*     summary="Authenticate user and get JWT token",
*     tags={"Authentication"},
*     @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*             required={"email","password"},
*             @OA\Property(property="email", type="string", example="paulo@example.com"),
*             @OA\Property(property="password", type="string", example="123456")
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="Successful login",
*         @OA\JsonContent(
*             @OA\Property(property="access_token", type="string"),
*             @OA\Property(property="token_type", type="string", example="Bearer"),
*             @OA\Property(property="expires_in", type="integer", example=86400),
*             @OA\Property(property="user", type="string", example="Paulo")
*         )
*     ),
*     @OA\Response(
*         response=401,
*         description="Invalid credentials"
*     )
* )
*/
    public function login(LoginRequest $request)
    {

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
