<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ManhwaController;
use App\Http\Controllers\Api\NewsletterController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    // ---------------- مانها و چپترها ----------------
    Route::prefix('manhuas')->controller(ManhwaController::class)->group(function () {
        Route::get('/',  'index');

    });

    Route::prefix('blogs')->controller(BlogController::class)->group(function () {
        Route::get('/',  'index');
        Route::get('/{id}',  'show');

    });
    Route::post('/newsletter', [NewsletterController::class, 'store']);


});
