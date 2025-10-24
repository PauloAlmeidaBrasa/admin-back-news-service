
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;


Route::prefix('user')->group(function () {
     // Admin routes
    Route::get('/get-users', [UserController::class,'getUsersByClientId'])->middleware(['access.level:3'])->name('users.all');
    Route::post('/add-user', [UserController::class,'store'])->middleware(['access.level:3'])->name('users.store');
    Route::post('/delete', [UserController::class,'delete'])->middleware(['access.level:3'])->name('users.delete');
});

