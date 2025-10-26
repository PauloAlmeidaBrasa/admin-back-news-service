<?php

namespace App\Services;

class BaseService
{
    protected function success($data = null, string $message = 'Success', string $code = 'SUCCESS'): array
    {
        return [
            'status' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
    }

    protected function error(string $message = 'Internal server error', string $code = 'INTERROR', $data = null): array
    {
        return [
            'status' => false,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
    }
}
