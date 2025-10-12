<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
// use App\Http\Controllers\AuthController;

// [App\Http\Controllers\Api\V1\AuthController::class, 'login']

// dd('sdAAAasd');
 Route::post('login', [AuthController::class, 'login']);
