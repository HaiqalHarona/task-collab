<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// =================Auth User Routes================

// Notice page for email verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Handler when email link is clicked
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

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
Route::middleware(['auth', 'verified'])->group(function () {

    // Show Dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
