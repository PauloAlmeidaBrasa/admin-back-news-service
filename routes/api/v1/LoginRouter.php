<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

 Route::post('login', [AuthController::class, 'login'])->name('auth.login');

 Route::get('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');        
