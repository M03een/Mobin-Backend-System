<?php

use App\Http\Middleware\ForceJsonResponse;
use App\Http\v1\Controllers\AuthController;
use App\Http\v1\Controllers\PointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware(ForceJsonResponse::class)->group(function (){
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/points', [PointController::class, 'learderboard']);
        Route::post('password/forget', [AuthController::class, 'forgetPassword']);
        Route::post('password/reset', [AuthController::class, 'resetPassword']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class,'me']);
            Route::post('/logout', [AuthController::class,'logout']);
            Route::post('/points', [PointController::class, 'store']);
        });
    });
});
