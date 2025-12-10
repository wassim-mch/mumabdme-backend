<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PermissionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/categories', [CategorieController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:manage_roles');
    Route::apiResource('/roles', RoleController::class)->middleware('permission:manage_roles');
    Route::post('/services/{service}/images', [GalleryController::class, 'store'])->middleware('permission:manage_services');
    Route::put('/services/{service}/images/{image}', [GalleryController::class, 'update'])->middleware('permission:manage_services'); 
    Route::delete('/services/{service}/images/{image}', [GalleryController::class, 'destroy'])->middleware('permission:manage_services');
    Route::apiResource('/services', ServiceController::class)->middleware('permission:manage_services');
    Route::apiResource('/categories', CategorieController::class)->middleware('permission:manage_categories');
    Route::post('/logout', [AuthController::class, 'logout']);
});
