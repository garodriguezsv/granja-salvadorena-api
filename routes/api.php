<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthController::class, 'index']);

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {

        Route::get('/me', [AuthController::class, 'me']);

        Route::post('/logout', [AuthController::class, 'logout']);

    });

});

    Route::middleware('auth:api')->group(function () {

        Route::apiResource('products', ProductController::class);

        Route::apiResource('categories', CategoryController::class);

        Route::get('/orders', [OrderController::class, 'index']);
        
        Route::post('/orders', [OrderController::class, 'store']);

        Route::get('/orders/{order}', [OrderController::class, 'show']);

        Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);

        Route::post('/orders/{order}/payment', [PaymentController::class, 'store']);

    });