<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ManhwaController;
use App\Http\Controllers\Api\NewsletterController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    // ---------------- مانها و چپترها ----------------
    Route::prefix('manhuas')->controller(ManhwaController::class)->group(function () {
        Route::get('/',  'index');
        Route::get('/{id}',  'show');
        Route::get('/chapters/{id}',  'chapters');

    });
    Route::prefix('contact_us')->controller(\App\Http\Controllers\ContactUsController::class)->group(function () {
        Route::post('/',  'store');
        Route::get('/blogs',  'blogs');


    });

    Route::prefix('blogs')->controller(BlogController::class)->group(function () {
        Route::get('/',  'index');
        Route::get('/{id}',  'show');

    });
    Route::post('/newsletter', [NewsletterController::class, 'store']);

    Route::prefix('auth')->group(function () {
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);          // ارسال کد تایید
        Route::post('/check-user-exists', [AuthController::class, 'checkUserExists']); // بررسی وجود کاربر
        Route::post('/register', [AuthController::class, 'register']);        // ثبت نام
        Route::post('/login', [AuthController::class, 'login']);              // ورود
        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']); // خروج
        Route::post('reset', [AuthController::class, 'resetPasswordWithOtp']);

    });
    Route::prefix('author')->group(function () {
        Route::post('/send-otp', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'profile']);          // ارسال کد تایید


    });
    Route::prefix('author')->group(function () {
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);          // ارسال کد تایید


    });
});
