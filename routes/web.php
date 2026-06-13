<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AiImageFilterCategoryController;
use App\Http\Controllers\AiImageFilterController;
use App\Http\Controllers\ApiListController;
use App\Http\Controllers\StickerCategoryController;
use App\Http\Controllers\StickerController;
use App\Http\Controllers\FontController;

// Root → login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // API Documentation page
    Route::get('/admin/api-list', [ApiListController::class, 'index'])->name('api-list');

    // AI Image Filter — Categories
    Route::prefix('admin/ai-image-filter-categories')->name('ai-image-filter-categories.')->group(function () {
        Route::get('/',               [AiImageFilterCategoryController::class, 'index'])->name('index');
        Route::get('/create',         [AiImageFilterCategoryController::class, 'create'])->name('create');
        Route::post('/',              [AiImageFilterCategoryController::class, 'store'])->name('store');
        Route::get('/order-list',     [AiImageFilterCategoryController::class, 'orderList'])->name('order-list');
        Route::post('/update-status', [AiImageFilterCategoryController::class, 'updateStatus'])->name('update-status');
        Route::post('/update-order',  [AiImageFilterCategoryController::class, 'updateOrder'])->name('update-order');
        Route::get('/{aiImageFilterCategory}/edit', [AiImageFilterCategoryController::class, 'edit'])->name('edit');
        Route::put('/{aiImageFilterCategory}',      [AiImageFilterCategoryController::class, 'update'])->name('update');
        Route::delete('/{aiImageFilterCategory}',   [AiImageFilterCategoryController::class, 'destroy'])->name('destroy');
    });

    // AI Image Filter — Filters
    Route::prefix('admin/ai-image-filters')->name('ai-image-filters.')->group(function () {
        Route::get('/',              [AiImageFilterController::class, 'index'])->name('index');
        Route::get('/create',        [AiImageFilterController::class, 'create'])->name('create');
        Route::post('/',             [AiImageFilterController::class, 'store'])->name('store');
        Route::get('/order-list',    [AiImageFilterController::class, 'orderList'])->name('order-list');
        Route::post('/update-order', [AiImageFilterController::class, 'updateOrder'])->name('update-order');
        Route::get('/{aiImageFilter}/edit', [AiImageFilterController::class, 'edit'])->name('edit');
        Route::put('/{aiImageFilter}',      [AiImageFilterController::class, 'update'])->name('update');
        Route::delete('/{aiImageFilter}',   [AiImageFilterController::class, 'destroy'])->name('destroy');
    });

    // Sticker — Categories
    Route::prefix('admin/sticker-categories')->name('sticker-categories.')->group(function () {
        Route::get('/',               [StickerCategoryController::class, 'index'])->name('index');
        Route::get('/create',         [StickerCategoryController::class, 'create'])->name('create');
        Route::post('/',              [StickerCategoryController::class, 'store'])->name('store');
        Route::get('/order-list',     [StickerCategoryController::class, 'orderList'])->name('order-list');
        Route::post('/update-status', [StickerCategoryController::class, 'updateStatus'])->name('update-status');
        Route::post('/update-order',  [StickerCategoryController::class, 'updateOrder'])->name('update-order');
        Route::get('/{stickerCategory}/edit', [StickerCategoryController::class, 'edit'])->name('edit');
        Route::put('/{stickerCategory}',      [StickerCategoryController::class, 'update'])->name('update');
        Route::delete('/{stickerCategory}',   [StickerCategoryController::class, 'destroy'])->name('destroy');
    });

    // Sticker — Sticker Images
    Route::prefix('admin/stickers')->name('stickers.')->group(function () {
        Route::get('/',              [StickerController::class, 'index'])->name('index');
        Route::get('/create',        [StickerController::class, 'create'])->name('create');
        Route::post('/',             [StickerController::class, 'store'])->name('store');
        Route::get('/{stickerCategory}/edit',            [StickerController::class, 'edit'])->name('edit');
        Route::put('/{stickerCategory}',                 [StickerController::class, 'update'])->name('update');
        Route::delete('/{stickerCategory}',              [StickerController::class, 'destroy'])->name('destroy');
        Route::post('/{stickerCategory}/remove-sticker', [StickerController::class, 'removeSticker'])->name('remove-sticker');
    });

    // Font
    Route::prefix('admin/fonts')->name('fonts.')->group(function () {
        Route::get('/',              [FontController::class, 'index'])->name('index');
        Route::get('/create',        [FontController::class, 'create'])->name('create');
        Route::post('/',             [FontController::class, 'store'])->name('store');
        Route::get('/order-list',    [FontController::class, 'orderList'])->name('order-list');
        Route::post('/update-order', [FontController::class, 'updateOrder'])->name('update-order');
        Route::get('/{font}/edit',   [FontController::class, 'edit'])->name('edit');
        Route::put('/{font}',        [FontController::class, 'update'])->name('update');
        Route::delete('/{font}',     [FontController::class, 'destroy'])->name('destroy');
    });

});
