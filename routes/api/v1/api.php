<?php

use Illuminate\Support\Facades\Route;

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

require  __DIR__.'/LoginRouter.php';

// Authenticated routes
Route::middleware(['jwt.verify'])->group(function () {
    require __DIR__.'/UserRouter.php';
    
    // Admin routes
    // Route::prefix('admin')->middleware('role:admin')->group(function () {
    //     require __DIR__.'/admin/user.php';
    // });
});





// Route::post('login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
