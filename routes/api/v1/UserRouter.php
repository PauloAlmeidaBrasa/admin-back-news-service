
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::prefix('user')->group(function () {
    Route::get('/get-users', [UserController::class,'getUsersByClientId'])->middleware(['auth', 'access.level:3'])->name('users.all');
    Route::post('/add-user', [UserController::class,'store'])->middleware(['auth', 'access.level:3'])->name('users.store');
});

