<?php

use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes (no authentication required)
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);

// Protected routes (admin only)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/books', [BookController::class, 'store'])->middleware('admin');
    Route::put('/books/{id}', [BookController::class, 'update'])->middleware('admin');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->middleware('admin');
});

