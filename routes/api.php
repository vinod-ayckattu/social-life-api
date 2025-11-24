<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
    
})->middleware('auth:sanctum');

Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/follow', [UserController::class, 'addInfluencer'])->middleware('auth:sanctum');
Route::post('/unfollow', [UserController::class, 'removeInfluencer'])->middleware('auth:sanctum');

Route::get('/posts', [PostController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'userProfile']);
Route::post('/users/store', [UserController::class, 'register']);
Route::post('/users/auth/store', [UserController::class, 'login']);
