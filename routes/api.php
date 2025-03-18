<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AuthController;

Route::apiResource('categories',categoryController::class);

Route::apiResource('tags', TagController::class);

Route::apiResource('courses', CourseController::class);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->post('/refresh', [AuthController::class, 'refresh']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'user']);
Route::middleware('auth:api')->put('/user', [AuthController::class, 'update']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
