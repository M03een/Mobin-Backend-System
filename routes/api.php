<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\LeaderBoardController;
use App\Http\Controllers\v1\PointController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware(ForceJsonResponse::class)->group(function (){
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/points/all', [LeaderBoardController::class, 'learderboard']);
        Route::get('/points/month', [LeaderBoardController::class, 'month']);
        Route::get('/points/week', [LeaderBoardController::class, 'week']);
        Route::get('/points/day', [LeaderBoardController::class, 'day']);
        Route::post('password/forget', [AuthController::class, 'forgetPassword']);
        Route::post('password/reset', [AuthController::class, 'resetPassword']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class,'me']);
            Route::post('/logout', [AuthController::class,'logout']);
            Route::post('/points', [PointController::class, 'store']);
        });
    });
});
