<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;


class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

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
    public function respondWithSuccess($data, string $feature, int $statusCode = 200, ?string $message = null)
    {
        return response()->json([
            'success' => true,
            'data' => [
                $feature => $data
            ],
        ], $statusCode);
    }
}
