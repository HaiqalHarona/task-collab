<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Controllers\AuthRoutes;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;

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
        return redirect()->route('login', ['tab' => 'register']);
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
Route::get('/auth/github/disconnect', [UserController::class, 'DisconnectGithub'])->name('social.disconnect');

// ==================================================

// Auththenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Show Dashboard
    Route::get('/', [AuthRoutes::class, 'dashboard'])->name('dashboard');

    // Show Profile
    Route::get('/profile', [AuthRoutes::class, 'profile'])->name('profile');

    // Show Projects
    Route::get('/projects', [AuthRoutes::class, 'projects'])->name('projects');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Create Project
    Route::post('/projects', [ProjectController::class, 'ProjectCreate'])->name('project.create');

    // Update User (profile view)
    Route::post('/profile', [UserController::class, 'UpdateProfile'])->name('profile.update');

    // Update Project
    Route::post('/projects/update', [ProjectController::class, 'ProjectUpdate'])->name('project.update');

    // Deactivate Project
    Route::post('/projects/deactivate', [ProjectController::class, 'ProjectDelete'])->name('project.delete');

    // Show Archived Projects
    Route::get('/projects/archived', [AuthRoutes::class, 'projectsArchived'])->name('projects.archived');

    // Restore Project
    Route::post('/projects/restore', [ProjectController::class, 'ProjectRestore'])->name('project.restore');

    // Send Member Invite (outside policy group — auth check done in controller)
    Route::post('/projects/invite/email', [ProjectMemberController::class, 'MemberInvite'])->name('project.invite.email');

    // Update member roles
    Route::post('/projects/members/roles', [ProjectMemberController::class, 'UpdateRoles'])->name('project.members.roles');

    // Project Board Policy Routes
    Route::middleware('can:roleView,project')->group(function () {

        // View Project Board
        Route::get('/projects/{project}', [AuthRoutes::class, 'projectBoard'])->name('project.board');

        // View Project Members
        Route::get('/projects/{project}/members', [AuthRoutes::class, 'projectMembers'])->name('project.members');

        // Add Tags
        Route::post('/projects/{project}/tags', [ProjectController::class, 'AddTags'])->name('project.tags.add');

        // Add Pools
        Route::post('/project/{project}/pool', [ProjectController::class, 'AddPools'])->name('project.pools.add');

        // Add Task
        Route::post('/project/{project}/task', [ProjectController::class, 'AddTasks'])->name('project.task.add');

    });

    // Accept Project Invite Route
    Route::get('/project/invite/{project}/email', [ProjectMemberController::class, 'AcceptInvite'])->name('project.invite.accept')->middleware('signed');
});
