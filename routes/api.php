<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\AuthorRequestController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ManhwaController;
use App\Http\Controllers\Api\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('/comments', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'comments'])->middleware('auth:sanctum');
Route::get('/test', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'test']);
Route::get('/a/comments/{id}', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'comments']);

Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/chapters/{chapter}/purchase', [ManhwaController::class, 'purchase']);
    });
    // ---------------- مانها و چپترها ----------------
    Route::prefix('manhuas')->controller(ManhwaController::class)->group(function () {
        Route::get('/', 'index');

        Route::get('/search', 'search');
        Route::get('/{id}', 'show')->middleware('optional.auth');;
        Route::get('/chapters/{id}', 'chapters')->middleware('optional.auth');
        Route::post('/toggle/{id}', 'toggle')->middleware('optional.auth');
        Route::post('/support', 'supportWithWallet')->middleware('optional.auth');


        Route::get('/comments/{id}', 'comments')->middleware('optional.auth');
        Route::post('/store/comments', 'store_comment')->middleware('auth:sanctum');
        Route::delete('/delete/comments/{id}', 'delete_comment')->middleware('auth:sanctum');
        Route::post('/edit/comments/{id}', 'edit_comment')->middleware('auth:sanctum');

    });

    Route::prefix('contact_us')->controller(\App\Http\Controllers\ContactUsController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/blogs', 'blogs');
    });

    Route::prefix('archive')->controller(\App\Http\Controllers\Api\ArchiveController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/get_category', 'get_category');
    });

    Route::post('/author-requests', [AuthorRequestController::class, 'store']);


    Route::prefix('blogs')->controller(BlogController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/popular', 'popular');
        Route::get('/category', 'category');
        Route::get('/{id}', 'show');


        Route::get('/comments/{id}', 'comments')->middleware('optional.auth');
        Route::post('/store/comments', 'store_comment')->middleware('auth:sanctum');
        Route::delete('/delete/comments/{id}', 'delete_comment')->middleware('auth:sanctum');
        Route::post('/edit/comments/{id}', 'edit_comment')->middleware('auth:sanctum');


    });

    Route::post('/newsletter', [NewsletterController::class, 'store']);
    Route::post('/manhuas/rate/{id}', [ManhwaController::class, 'rate'])->middleware('auth:sanctum');
    Route::post('/donate', [ManhwaController::class, 'donate'])->middleware('auth:sanctum');


    Route::prefix('auth')->group(function () {
        Route::post('/sendOtp', [AuthController::class, 'sendOtp']);
        Route::post('/check', [AuthController::class, 'checkUserExists']);
        Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
        Route::post('reset', [AuthController::class, 'resetPasswordWithOtp']);
    });


    Route::prefix('author')->group(function () {
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        Route::get('/views', [\App\Http\Controllers\Api\Auther\viewsController::class, 'index'])->middleware('auth:sanctum');

    });
    Route::prefix('user_ticket')->middleware('auth:sanctum')->controller(\App\Http\Controllers\Api\Panel\TicketController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{id}', 'show');
        Route::post('/store', 'store');
        Route::post('/update/{id}', 'update');

    });
    Route::prefix('library')->middleware('auth:sanctum')->controller(\App\Http\Controllers\Api\Panel\LibraryController::class)->group(function () {
        Route::get('/', 'readingList');
        Route::post('/like/{id}', 'deleteOrLikedManhwa');
        Route::get('/completed', 'completedList');
        Route::get('/liked', 'likedManhwas');
        Route::get('/delete', 'deleteManhwas');
        Route::get('/all', 'userAllManhwas');
    });
    Route::prefix('dashboard')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Panel\DashboardController::class, 'dashboard']);
    });
    Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'index']);
        Route::get('/comments', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'comments']);
        Route::post('/update', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'profile']);
        Route::post('/change-password', [\App\Http\Controllers\Api\Panel\ProfileController::class, 'changePassword']);
    });
    Route::prefix('wallet')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Panel\WalletController::class, 'index']);
        Route::post('/withdrawal', [\App\Http\Controllers\Api\Panel\WalletController::class, 'withdrawal']);
        Route::post('/deposit', [\App\Http\Controllers\Api\Panel\WalletController::class, 'deposit']);

    });


    Route::prefix('author')->middleware('auth:sanctum')->group(function () {
        Route::prefix('manhua')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'index']);
            Route::post('/store', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'store']);
            Route::get('/comment/{id}', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'comments']);
            Route::post('/update/{manhwa}', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'update']);
            Route::delete('/delete/{id}', [\App\Http\Controllers\Api\Auther\ManhwaController::class, 'destroy']);
        });
        Route::prefix('chapters')->middleware('auth:sanctum')->group(function () {
            Route::get('/index/{id}', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'index']);
            Route::get('/show/{id}', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'chapters']);
            Route::post('/store', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'store']);
            Route::post('/update/{id}', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'update']);
            Route::delete('/delete/{id}', [\App\Http\Controllers\Api\Auther\ChapterController::class, 'delete']);
        });
    });
});
