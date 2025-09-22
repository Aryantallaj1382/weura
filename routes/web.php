<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ComingSoonManhuaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManhwaController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;


Route::get('/mt', function () {
    Artisan::Call('migrate', ['--force' => true]);
    dd(Artisan::output());
});
Route::get('/optimize', function () {
    Artisan::call('optimize');
    dd(Artisan::output());
});

// Auth Routes
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout22');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('coming_soon_manhuas', ComingSoonManhuaController::class);
    Route::resource('banners', BannerController::class)->only(['index', 'store', 'destroy']);

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply');


    Route::resource('categories', CategoryController::class);
    Route::resource('blog_categories', BlogCategoryController::class);
    Route::resource('blog', BlogController::class);


    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::patch('transactions/{transaction}/status', [UserController::class, 'updateStatus'])->name('transactions.updateStatus');
    // Manhwa
    Route::get('/manhwa', [ManhwaController::class, 'index'])->name('manhwa.index');
    Route::get('/manhwa/create', [ManhwaController::class, 'create'])->name('manhwa.create');
    Route::post('/manhwa/store', [ManhwaController::class, 'store'])->name('manhwa.store');
    Route::get('/manhwa/{id}', [ManhwaController::class, 'show'])->name('manhwa.show');
    Route::get('/manhwa/{id}/edit', [ManhwaController::class, 'edit'])->name('manhwa.edit');
    Route::put('/manhwa/{id}', [ManhwaController::class, 'update'])->name('manhwa.update');
    Route::delete('/manhwa/destroy/{id}', [ManhwaController::class, 'destroy'])->name('manhwa.destroy');

    Route::get('/admin/manhwas/{manhwa}/chapters/create', [ManhwaController::class, 'create_chapter'])->name('chapters.create');
    Route::post('/admin/manhwas/{manhwa}/chapters', [ManhwaController::class, 'store_chapter'])->name('chapters.store');

    Route::get('/admin/manhwas/{id}/chapters/edit', [ManhwaController::class, 'edit_chapter'])->name('chapters.edit');
    Route::put('/admin/manhwas/{id}/chapters/update', [ManhwaController::class, 'update_chapter'])->name('chapters.update');
    Route::delete('/manhwa/{id}/chapters/delete', [ManhwaController::class, 'destroy_chapter'])->name('chapters.destroy');

    Route::get('/chapter/{id}', [ManhwaController::class, 'show_chapter'])->name('chapter.show');
});



require __DIR__.'/auth.php';
