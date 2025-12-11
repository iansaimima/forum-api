<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\TopicCommentController;
use App\Http\Controllers\Api\TopicLikeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Protected routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Topics
    Route::prefix('topics')->group(function () {
        Route::get('/', [TopicController::class, 'index']);
        Route::post('/', [TopicController::class, 'store']);
        Route::get('/{id}', [TopicController::class, 'show']);
        Route::put('/{id}', [TopicController::class, 'update']);
        Route::delete('/{id}', [TopicController::class, 'destroy']);

        // Topic Comments
        Route::get('/{topicId}/comments', [TopicCommentController::class, 'index']);
        Route::post('/{topicId}/comments', [TopicCommentController::class, 'store']);
        Route::put('/{topicId}/comments/{commentId}', [TopicCommentController::class, 'update']);
        Route::delete('/{topicId}/comments/{commentId}', [TopicCommentController::class, 'destroy']);

        // Topic Likes
        Route::post('/{topicId}/like', [TopicLikeController::class, 'toggle']);
        Route::get('/{topicId}/likes', [TopicLikeController::class, 'users']);
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/search', [UserController::class, 'search']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/{id}/follow', [UserController::class, 'follow']);
        Route::delete('/{id}/follow', [UserController::class, 'unfollow']);
        Route::get('/{id}/followers', [UserController::class, 'followers']);
        Route::get('/{id}/following', [UserController::class, 'following']);
    });
});
