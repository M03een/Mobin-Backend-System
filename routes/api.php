<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PointController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// TODO Visioning the api with prefix v1
Route::middleware(ForceJsonResponse::class)->group(function (){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/points', [PointController::class, 'learderboard']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class,'me']);
        Route::post('/logout', [AuthController::class,'logout']);
        Route::post('/points', [PointController::class, 'store']);
    });
});
