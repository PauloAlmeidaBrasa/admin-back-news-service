<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Public routes
require __DIR__.'/login.php';

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    require __DIR__.'/user.php';
    
    // Admin routes
    // Route::prefix('admin')->middleware('role:admin')->group(function () {
    //     require __DIR__.'/admin/user.php';
    // });
});



// Route::post('login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
