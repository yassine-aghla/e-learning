<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\Api\MentorController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\BadgeController;

use App\Http\Controllers\StripeController;

Route::apiResource('categories',categoryController::class);

Route::apiResource('tags', TagController::class);

Route::apiResource('courses', CourseController::class);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->post('/refresh', [AuthController::class, 'refresh']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'user']);
Route::middleware('auth:api')->post('/user', [AuthController::class, 'update']);
Route::middleware('auth:api')->delete('/users/{id}', [AuthController::class, 'destroy']);


Route::middleware('auth:api')->post('/courses/{id}/enroll', [EnrollmentController::class, 'enroll']);
Route::middleware('auth:api')->get('/courses/{id}/enrollments', [EnrollmentController::class, 'getEnrollmentsByCourse']);
Route::middleware('role:Mentor|Admin')->group(function () {
Route::middleware('auth:api')->put('/enrollments/{id}', [EnrollmentController::class, 'updateEnrollmentStatus']);
});
Route::middleware('auth:api')->delete('/enrollments/{id}', [EnrollmentController::class, 'deleteEnrollment']);


Route::get('/stats/courses', [StatsController::class, 'getCourseStats']);
Route::get('/stats/categories', [StatsController::class, 'getCategoryStats']);
Route::get('/stats/tags', [StatsController::class, 'getTagStats']);
Route::get('/stats/enrollments', [StatsController::class, 'getEnrollmentStats']);

Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

Route::get('/permissions', [PermissionController::class, 'index']);
Route::post('/permissions', [PermissionController::class, 'store']);
Route::put('/permissions/{id}', [PermissionController::class, 'update']);
Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);

Route::post('/users/{userId}/assign-role', [UserController::class, 'assignRole']);
Route::delete('/users/{userId}/remove-role', [UserController::class, 'removeRole']);

Route::middleware('role:Mentor')->group(function () {
Route::post('/courses/{id}/videos', [VideoController::class, 'store']);
Route::get('/courses/{id}/videos', [VideoController::class, 'index']);
Route::get('/videos/{id}', [VideoController::class, 'show']);
Route::put('/videos/{id}', [VideoController::class, 'update']);
Route::delete('/videos/{id}', [VideoController::class, 'destroy']);
});


Route::get('mentors/{id}/courses', [MentorController::class, 'getMentorCourses']);
Route::get('mentors/{id}/students', [MentorController::class, 'getMentorStudents']);
Route::get('mentors/{id}/performance', [MentorController::class, 'getMentorPerformance']);



Route::get('students/{id}/courses', [StudentController::class, 'getStudentCourses']);
Route::get('students/{id}/progress', [StudentController::class, 'getStudentProgress']);


// Route::post('/create-payment', [PayPalController::class, 'createPayment']);
// Route::get('/success', [PayPalController::class, 'executePayment'])->name('paypal.success');
// Route::get('/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

Route::get("/payment",[StripeController::class,"index"])->name("payment.index");
Route::post("/payment/checkout/{id}",[EnrollmentController::class,"enroll"])->name("payment.checkout");
Route::get("/payment/success/{course}",[StripeController::class,"success"])->name("payment.success");
Route::get('/payments/history', [EnrollmentController::class, 'paymentHistory']);
Route::get('/mentors', [MentorController::class, 'search']);

Route::post('/badges', [BadgeController::class, 'store']);
Route::put('/badges/{id}', [BadgeController::class, 'update']);
Route::delete('/badges/{id}', [BadgeController::class, 'destroy']);

Route::get('/students/{id}/badges', [StudentBadgeController::class, 'index']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

