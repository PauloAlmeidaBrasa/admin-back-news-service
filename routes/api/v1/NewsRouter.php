<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\news\NewsController;


Route::prefix('news')->group(function () {
     // Admin routes
    Route::get('/get-news', [NewsController::class,'newsByClientId'])->middleware(['access.level:3'])->name('news.all');
    Route::post('/add-news', [NewsController::class,'store'])->middleware(['access.level:3'])->name('news.store');
    Route::post('/delete', [NewsController::class,'delete'])->middleware(['access.level:3'])->name('news.delete');
    Route::patch('/update/{id}', [NewsController::class,'update'])->middleware(['access.level:3'])->name('news.update');
});