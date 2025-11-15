<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ActivityController as PublicActivityController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ShelfBookController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);
Route::get('/buku', [BookController::class, 'catalog'])->name('books.index');
Route::get('/book/{id}', [BookController::class, 'show'])->name('book.show');
Route::get('/about', [AboutController::class, 'show'])->name('about');
Route::get('/kegiatan', [PublicActivityController::class, 'index'])->name('activities.index');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/import-google-books', [DashboardController::class, 'importGoogleBooks'])->name('import.google-books');
    Route::post('/import-google-novels', [DashboardController::class, 'importGoogleNovels'])->name('import.google-novels');
    Route::post('/import-google-comics', [DashboardController::class, 'importGoogleComics'])->name('import.google-comics');
    
    Route::resource('books', BookController::class);
    Route::resource('shelf-books', ShelfBookController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('activities', AdminActivityController::class);
    
    Route::get('/about', [AboutController::class, 'edit'])->name('about.edit');
    Route::put('/about', [AboutController::class, 'update'])->name('about.update');
});

// Auth routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
