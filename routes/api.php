<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\AuthorRequestController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ManhwaController;
use App\Http\Controllers\Api\NewsletterController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    // ---------------- مانها و چپترها ----------------
    Route::prefix('manhuas')->controller(ManhwaController::class)->group(function () {
        Route::get('/',  'index');
        Route::get('/{id}',  'show');
        Route::get('/chapters/{id}',  'chapters')->middleware('optional.auth');
        Route::get('/toggle/{id}',  'toggle')->middleware('optional.auth');
        Route::post('/support',  'supportWithWallet')->middleware('optional.auth');

    });
    Route::prefix('contact_us')->controller(\App\Http\Controllers\ContactUsController::class)->group(function () {
        Route::post('/',  'store');
        Route::get('/blogs',  'blogs');


    });

    Route::prefix('archive')->controller(\App\Http\Controllers\Api\ArchiveController::class)->group(function () {
        Route::get('/',  'index');


    });
    Route::post('/author-requests', [AuthorRequestController::class, 'store']);

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
        Route::middleware('api.auth')->post('/logout', [AuthController::class, 'logout']); // خروج
        Route::post('reset', [AuthController::class, 'resetPasswordWithOtp']);

    });

    Route::prefix('author')->group(function () {
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);          // ارسال کد تایید
    });

    Route::prefix('user_ticket')->middleware('api.auth')->controller(\App\Http\Controllers\Api\Panel\TicketController::class)->group(function () {
        Route::get('/',  'index');
        Route::get('/show/{id}',  'show');
        Route::post('/store',  'store');
        Route::post('/update/{id}',  'update');

    });
    Route::prefix('library')->middleware('api.auth')->controller(\App\Http\Controllers\Api\Panel\LibraryController::class)->group(function () {
        Route::get('/',  'readingList');
        Route::get('/completed',  'completedList');
        Route::get('/liked',  'likedManhwas');
        Route::get('/all', 'userAllManhwas');

    });
    Route::prefix('dashboard')->middleware('api.auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Panel\DashboardController::class, 'dashboard']);          // ارسال کد تایید
    });
    Route::prefix('profile')->middleware('api.auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'index']);
        Route::post('/update', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'profile']);
        Route::post('/change-password', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'changePassword']);
    });



    Route::prefix('author')->middleware('api.auth')->group(function () {
        Route::prefix('manhua')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'index']);
            Route::post('/store', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'store']);
            Route::post('/update/{manhwa}', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'update']);
            Route::delete('/delete/{manhwa}', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'destroy']);
        });
        Route::prefix('chapters')->middleware('api.auth')->group(function () {
            Route::get('/index/{id}', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'index']);
            Route::post('/store', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'store']);
            Route::post('/update/{id}', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'update']);
            Route::delete('/delete/{id}', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'delete']);
        });
    });
});
