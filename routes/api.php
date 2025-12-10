<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategorieController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/services/{service}/images', [GalleryController::class, 'store']);
    Route::put('/services/{service}/images/{image}', [GalleryController::class, 'update']); 
    Route::delete('/services/{service}/images/{image}', [GalleryController::class, 'destroy']);
    Route::apiResource('services', ServiceController::class)->middleware('permission:manage_services');
    Route::apiResource('categories', CategorieController::class)->middleware('permission:manage_categories');
    Route::post('/logout', [AuthController::class, 'logout']);
});
