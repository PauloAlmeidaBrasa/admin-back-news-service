<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function sendSuccess($data, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Send a standardized JSON error response.
     */
    protected function sendError(string $message = 'Error', int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $status);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token['token'],
            'token_type' => $token['token_type'],
            'expires_in' => $token['expires_in'],
            'user' => $token['user']
        ]);
    }
}
