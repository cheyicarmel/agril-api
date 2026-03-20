<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\FarmController;
use App\Http\Controllers\API\V1\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });
    });

    Route::prefix('farms')->group(function () {
        Route::get('/', [FarmController::class, 'index']);
        Route::get('/{farm}', [FarmController::class, 'show']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user/my-farms', [FarmController::class, 'myFarms']);
            Route::post('/', [FarmController::class, 'store']);
            Route::patch('/{farm}', [FarmController::class, 'update']);
            Route::delete('/{farm}', [FarmController::class, 'destroy']);
        });
    });

    Route::prefix('stocks')->group(function () {
        Route::get('/', [StockController::class, 'index']);
        Route::get('/{stock}', [StockController::class, 'show']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user/my-stocks', [StockController::class, 'myStocks']);
            Route::post('/', [StockController::class, 'store']);
            Route::patch('/{stock}', [StockController::class, 'update']);
            Route::patch('/{stock}/status', [StockController::class, 'updateStatus']);
            Route::delete('/{stock}', [StockController::class, 'destroy']);
        });
    });

});