<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ImagesController;

Route::get('/images', [ImagesController::class, 'index'])->name('images.list');
Route::post('/images/upload', [ImagesController::class, 'upload'])->name('images.upload');
Route::delete('/images/{id}', [ImagesController::class, 'destroy'])->name('images.destroy');
Route::get('/images/download/{image}', [ImagesController::class, 'downloadFile'])->name('images.download');
