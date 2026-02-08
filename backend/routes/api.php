<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

/**
 * API Routes for Blog Application
 * All routes return JSON responses and are prefixed with /api
 */

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
});

// Email verification route (signed URL)
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');

// Public routes for reading content
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
    });
    
    // Post management routes (authenticated users only)
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::put('/{slug}', [PostController::class, 'update']);
        Route::delete('/{slug}', [PostController::class, 'destroy']);
    });
    
    // Category management routes (authenticated users only)
    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{slug}', [CategoryController::class, 'update']);
        Route::delete('/{slug}', [CategoryController::class, 'destroy']);
    });
    
    // Get authenticated user info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });
});

/**
 * API Route Structure:
 * 
 * Public Routes:
 * POST   /api/auth/register     - Register new user
 * POST   /api/auth/login        - Login user
 * GET    /api/posts             - Get all posts (with filters)
 * GET    /api/posts/{slug}      - Get single post
 * GET    /api/categories        - Get all categories
 * GET    /api/categories/{slug} - Get single category with posts
 * 
 * Protected Routes (require Bearer token):
 * POST   /api/auth/logout       - Logout user
 * GET    /api/auth/profile      - Get user profile
 * GET    /api/user              - Get authenticated user
 * 
 * POST   /api/posts             - Create new post
 * PUT    /api/posts/{slug}      - Update post
 * DELETE /api/posts/{slug}      - Delete post
 * 
 * POST   /api/categories        - Create new category
 * PUT    /api/categories/{slug} - Update category
 * DELETE /api/categories/{slug} - Delete category
 */