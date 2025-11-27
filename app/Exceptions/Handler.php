<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (\Throwable $e, $request) {

            $errorsCodeToHandle = ['42S02'];  // identifying errors related to the database
            if(in_array($e->getCode(),$errorsCodeToHandle)) {  
                Log::error([
                    'errorMessage' =>  $e->getMessage(),
                    'file' => $e->getFile(),
                    'number' => $e->getLine()
                ]);
                 return response()->json([
                    'success' => false,
                    'message' => 'Internal Server error',
                ], 500);
            }
        });
    }

    protected function unauthenticated($request, \Throwable $exception)
    {
        return response()->json([
            'error' => 'Unauthenticated',
        ], 401);
    }
}
