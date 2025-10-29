
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\category\CategoryController;


Route::prefix('category')->group(function () {
     // Admin routes
    Route::get('/get-category', [CategoryController::class,'categoryByClientId'])->middleware(['access.level:3'])->name('category.all');
    Route::post('/add-category', [CategoryController::class,'store'])->middleware(['access.level:3'])->name('category.store');
    Route::post('/delete', [CategoryController::class,'delete'])->middleware(['access.level:3'])->name('category.delete');
    Route::patch('/update/{id}', [CategoryController::class,'update'])->middleware(['access.level:3'])->name('category.update');
});
