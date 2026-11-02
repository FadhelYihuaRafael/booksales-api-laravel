<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AuthController;

// -------------------------------------------------------------
// Authentication Routes
// -------------------------------------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/books', [BookController::class, 'index']);

// -------------------------------------------------------------
// Public Routes: Author & Genre (Read All & Show)
// Dapat diakses untuk semua orang, bahkan yang belum melakukan autentikasi
// -------------------------------------------------------------
Route::apiResource('genres', GenreController::class)->only(['index', 'show']);
Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);

// -------------------------------------------------------------
// Protected Routes: Author & Genre (Create, Update, Destroy)
// Hanya dapat diakses oleh admin
// -------------------------------------------------------------
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('genres', GenreController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('authors', AuthorController::class)->only(['store', 'update', 'destroy']);
});
