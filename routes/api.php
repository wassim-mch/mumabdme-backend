<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RdvController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PermissionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}', [ServiceController::class, 'show']);
Route::get('/categories', [CategorieController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:manage_roles');
    Route::apiResource('/roles', RoleController::class)->middleware('permission:manage_roles');
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->middleware('permission:manage_services');
    Route::apiResource('/service', ServiceController::class)->middleware('permission:manage_services');
    Route::apiResource('/categorie', CategorieController::class)->middleware('permission:manage_categories');
    Route::get('/admin/rdvs', [RdvController::class, 'indexAll'])->middleware('permission:manage_rdvs_own');
    Route::put('/admin/rdvs/{rdv}', [RdvController::class, 'update'])->middleware('permission:manage_rdvs_own');
    Route::get('/rdvs', [RdvController::class, 'index'])->middleware('permission:manage_rdv'); 
    Route::post('/rdvs', [RdvController::class, 'store'])->middleware('permission:manage_rdv'); 
    Route::delete('/rdvs/{rdv}', [RdvController::class, 'destroy'])->middleware('permission:manage_rdv'); 
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:manage_users');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:manage_users');
    Route::delete('/users', [UserController::class, 'destroy'])->middleware('permission:manage_users');
    Route::put('/user/update', [UserController::class, 'updateProfile']);
    Route::put('/user/update-password', [UserController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
