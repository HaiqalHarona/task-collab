<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;


// =================Auth User Routes================

// Notice page for email verification
Route::get('/email/verify', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('dashboard');
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Handler when email link is clicked
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return "Email Verified! You can close this tab and return to your original window.";
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

    // Forgot Password View (email input form)
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('forgot.password');

    // Forgot Password Handler
    Route::post('/forgot-password', [UserController::class, 'ForgotPassword'])->name('password.email');

    // Reset Password Form
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    // Reset Password Email Form Handler
    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    })->middleware('guest')->name('password.update');
});

// Socialite Routes
Route::get('/auth/{provider}/redirect', [UserController::class, 'RedirectToProvider'])->where('provider', 'google|github')->name('social.redirect');
Route::get('/auth/{provider}/callback', [UserController::class, 'ProviderCallback'])->where('provider', 'google|github')->name('social.callback');

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
