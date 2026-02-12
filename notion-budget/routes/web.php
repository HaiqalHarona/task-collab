<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// =================Auth User Routes================

Route::middleware(['guest'])->group(function () {
    // Show Auth Pages
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // Auth Post Requests
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout']);

    // Socialite Routes
    Route::get('/auth/{provider}/redirect', [UserController::class, 'RedirectToProvider'])->where('provider', 'google|github')->name('social.redirect');
    Route::get('/auth/{provider}/callback', [UserController::class, 'ProviderCallback'])->where('provider', 'google|github')->name('social.callback');
});

// ==================================================

// Auththenticated routes
Route::middleware(['auth'])->group(function () {

    // Show Dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
