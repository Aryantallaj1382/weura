<?php

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\AuthorRequestController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ComingSoonManhuaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManhwaController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\SystemSettingController;
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
Route::get('/', [DashboardController::class, 'index']);


Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout22');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('newsletters')->name('newsletters.')->group(function () {
        Route::get('/', [NewsletterController::class, 'index'])->name('index');
        Route::delete('/{id}', [NewsletterController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('author')->group(function () {
        Route::get('/requests', [AuthorRequestController::class, 'index'])->name('author-requests.index');
        Route::get('/requests/{id}', [AuthorRequestController::class, 'show'])->name('author-requests.show');
    });

    Route::prefix('system-settings')->group(function () {
        Route::get('/suggested', [SystemSettingController::class, 'editSuggested'])->name('suggested.edit');
        Route::put('/suggested', [SystemSettingController::class, 'updateSuggested'])->name('suggested.update');
    });

    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

    Route::resource('coming_soon_manhuas', ComingSoonManhuaController::class);
    Route::resource('banners', BannerController::class)->only(['index', 'store', 'destroy']);

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply');


    Route::resource('categories', CategoryController::class);
    Route::resource('blog_categories', BlogCategoryController::class);
    Route::resource('blog', BlogController::class);

    Route::get('/author', [AuthorController::class, 'index'])->name('author.index');
    Route::get('/author/{id}', [AuthorController::class, 'show'])->name('author.show');
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
