<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1','middleware' => ['cors', 'json']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);

        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::get('user', [AuthController::class, 'user']);
            Route::post('refresh-token', [AuthController::class, 'refreshToken']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    //task routes
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::apiResource('tasks',TaskController::class);
    });
    
});