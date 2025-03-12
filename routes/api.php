<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\CourseController;

Route::apiResource('categories',categoryController::class);

Route::apiResource('tags', TagController::class);


Route::apiResource('courses', CourseController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
