<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $credentials = $request->only('email', 'password');

        // dd( $credentials);


        $token = $this->authService->login($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user()
    {
        $user = $this->authService->getAuthenticatedUser();
        return response()->json($user);
    }
}





// namespace App\Http\Controllers;
// use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Auth;
// use Tymon\JWTAuth\Facades\JWTAuth;
// use App\Models\User;

// class AuthController extends Controller
// {
//     public function login(Request $request)
//     {
//         dd('1234');
//         $credentials = $request->only('email', 'password');

//         if (!$token = JWTAuth::attempt($credentials)) {
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }

//         return response()->json([
//             'token' => $token,
//             'token_type' => 'bearer',
//             'expires_in' => JWTAuth::factory()->getTTL() * 60,
//         ]);
//     }

//     public function logout()
//     {
//         JWTAuth::invalidate(JWTAuth::getToken());
//         return response()->json(['message' => 'Successfully logged out']);
//     }

//     public function user()
//     {
//         return response()->json(Auth::user());
//     }
// }