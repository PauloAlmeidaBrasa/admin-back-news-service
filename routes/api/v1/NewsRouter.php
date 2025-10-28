
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\NewsController;


Route::prefix('news')->group(function () {
     // Admin routes
    Route::get('/get-news', [NewsController::class,'newsByClientId'])->middleware(['access.level:3'])->name('news.all');
    // Route::post('/add-news', [UserController::class,'store'])->middleware(['access.level:3'])->name('users.store');
    // Route::post('/delete', [UserController::class,'delete'])->middleware(['access.level:3'])->name('users.delete');
    // Route::patch('/update/{id}', [UserController::class,'update'])->middleware(['access.level:3'])->name('users.update');
});

