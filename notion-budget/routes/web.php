<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// =================Auth User Routes================

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function(){
    return view('auth.register');
})->name('register');

// Auth Post Requests
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// ==================================================

// Auththenticated routes
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');