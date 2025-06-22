
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



Route::prefix('user')->group(function () {
    Route::get('/users-by-client', [UserController::class,'getUsersByClientId'])->middleware(['auth', 'access.level:3']);
    Route::post('/users-add', [UserController::class,'store'])->middleware(['auth', 'access.level:3']);
});

