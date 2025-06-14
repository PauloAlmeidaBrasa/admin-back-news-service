
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


// Route::middleware(['jwt.verify'])->group(function () {
    Route::get('/users', [UserController::class,'index']);
// });

// Route::middleware('auth:sanctum')->group(function () {
    
//     // User Resource Routes
//     Route::prefix('users')->group(function () {
//         // Get all users (with pagination)
//         Route::get('/', [UserController::class, 'index']);
        
//         // Create new user
//         Route::post('/', [UserController::class, 'store'])
//             ->middleware('permission:users.create');
        
//         // Get specific user
//         Route::get('/{user}', [UserController::class, 'show'])
//             ->where('user', '[0-9]+');
        
//         // Update user
//         Route::put('/{user}', [UserController::class, 'update'])
//             ->middleware('permission:users.update')
//             ->where('user', '[0-9]+');
        
//         // Delete user
//         Route::delete('/{user}', [UserController::class, 'destroy'])
//             ->middleware('permission:users.delete')
//             ->where('user', '[0-9]+');
        
//         // User profile (current authenticated user)
//         Route::prefix('me')->group(function () {
//             Route::get('/', [UserController::class, 'profile']);
//             Route::put('/', [UserController::class, 'updateProfile']);
//         });
//     });
// });