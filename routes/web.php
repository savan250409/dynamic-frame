<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

// Root → login (or dashboard if already logged in)
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
