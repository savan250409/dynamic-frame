<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AiImageFilterApiController;
use App\Http\Controllers\Api\StickerApiController;
use App\Http\Controllers\Api\FontApiController;
use App\Http\Controllers\Api\DoodleApiController;
use App\Http\Controllers\Api\FilterApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AI Image Filter API — Bearer token required
Route::prefix('ai-image-filter')->middleware('api.token')->group(function () {
    Route::get('/categories',        [AiImageFilterApiController::class, 'getCategories']);
    Route::post('/get-by-category',  [AiImageFilterApiController::class, 'getFiltersByCategoryId']);
});

// Sticker API — Bearer token required
Route::prefix('sticker')->middleware('api.token')->group(function () {
    Route::get('/get-stickers', [StickerApiController::class, 'getStickers']);
});

// Font API — Bearer token required
Route::prefix('font')->middleware('api.token')->group(function () {
    Route::get('/get-fonts', [FontApiController::class, 'getFonts']);
});

// Doodle API — Bearer token required
Route::prefix('doodle')->middleware('api.token')->group(function () {
    Route::get('/get-doodles', [DoodleApiController::class, 'getDoodles']);
});

// Filter API — Bearer token required
Route::prefix('filter')->middleware('api.token')->group(function () {
    Route::get('/get-all-filters', [FilterApiController::class, 'getAllFilters']);
});
