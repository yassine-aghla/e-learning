<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EnrollmentController;

Route::apiResource('categories',categoryController::class);

Route::apiResource('tags', TagController::class);

Route::apiResource('courses', CourseController::class);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->post('/refresh', [AuthController::class, 'refresh']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'user']);
Route::middleware('auth:api')->post('/user', [AuthController::class, 'update']);



Route::middleware('auth:api')->post('/courses/{id}/enroll', [EnrollmentController::class, 'enroll']);
Route::middleware('auth:api')->get('/courses/{id}/enrollments', [EnrollmentController::class, 'getEnrollmentsByCourse']);
Route::middleware('auth:api')->put('/enrollments/{id}', [EnrollmentController::class, 'updateEnrollmentStatus']);
Route::middleware('auth:api')->delete('/enrollments/{id}', [EnrollmentController::class, 'deleteEnrollment']);
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

