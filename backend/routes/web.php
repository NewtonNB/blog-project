<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

// Public routes
Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/login', [WebController::class, 'showLogin'])->name('login');
Route::post('/login', [WebController::class, 'login']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [WebController::class, 'logout'])->name('logout');
});
