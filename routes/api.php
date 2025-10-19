<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\SermonController;
// use App\Http\Controllers\Api\ScholarController;
// use App\Http\Controllers\Api\LectureController;
// use App\Http\Controllers\Api\ThinkerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// TODO: إنشاء API Controllers
// مسارات المصادقة
// Route::prefix('auth')->group(function () {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
//     Route::post('/reset-password', [AuthController::class, 'resetPassword']);
// });

// المسارات العامة (بدون مصادقة)
// Route::apiResource('sermons', SermonController::class)->only(['index', 'show']);
// Route::apiResource('scholars', ScholarController::class)->only(['index', 'show']);
// Route::apiResource('lectures', LectureController::class)->only(['index', 'show']);
// Route::apiResource('thinkers', ThinkerController::class)->only(['index', 'show']);

// البحث
// Route::get('/search', [SermonController::class, 'search']);

// المسارات المحمية (تتطلب مصادقة)
// Route::middleware('auth:sanctum')->group(function () {
//     // معلومات المستخدم
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });

//     Route::post('/logout', [AuthController::class, 'logout']);

//     // إدارة المحتوى (للمستخدمين المصرح لهم)
//     Route::middleware('role:member|scholar|admin')->group(function () {
//         Route::apiResource('sermons', SermonController::class)->except(['index', 'show']);
//     });

//     // إدارة العلماء والفتاوى (للعلماء والمشرفين)
//     Route::middleware('role:scholar|admin')->group(function () {
//         Route::apiResource('scholars', ScholarController::class)->except(['index', 'show']);
//         Route::post('/fatwas', [ScholarController::class, 'storeFatwa']);
//     });

//     // إدارة شاملة (للمشرفين فقط)
//     Route::middleware('role:admin')->group(function () {
//         Route::apiResource('lectures', LectureController::class)->except(['index', 'show']);
//         Route::apiResource('thinkers', ThinkerController::class)->except(['index', 'show']);

//         // إدارة المستخدمين
//         Route::get('/users', [AuthController::class, 'users']);
//         Route::put('/users/{id}/role', [AuthController::class, 'updateUserRole']);
//         Route::put('/users/{id}/status', [AuthController::class, 'updateUserStatus']);
//     });
// });
