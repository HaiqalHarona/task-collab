<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{

    public function register(Request $request)
    {
        // Form input retrieval
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:6|confirmed'

        ]);
        // Data validation is done in the form request
        // Data Insert
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Initiate email verification process (send email)
        event(new Registered($user));

        // Start the session
        Auth::login($user);

        // Redirect to to email notification page
        return redirect()->route('verification.notice')->with('success', "Thanks for signing up! Before getting started, we need to verify your email address, an email was sent to {$validated['email']}.");
    }

    public function login(Request $request)
    {
        // Form input retrieval
        $cred = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6'

        ]);

        // Login Query
        if (Auth::attempt($cred)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Login Successful');
        }

        return back()->withErrors([
            'LoginError' => 'The provided credentials are do not match our records.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout Successful');
    }

    // Forgot Password Handler
    public function ForgotPassword(Request $request)
    {
        // Validate email input
        $request->validate([
            'email' => 'required|email|max:255|exists:user,email',
        ]);

        //Get user data check user exists 
        $user = User::where('email', $request->email)->first();

        if ($user && !empty($user->google_id)) {
            // Redirect user directly to the Google OAuth route
            return redirect()->route('social.redirect', 'google')
                ->with('success', 'This email is linked to Google. Redirecting you to sign in securely.');
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Socialite Redirect
    public function RedirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Get user info from provider
    public function ProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            dd('Socialite Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Login with ' . ucfirst($provider) . ' failed. Please try again.');
        }
        // Check if email is provided for github users
        $email = $socialUser->getEmail();
        if (empty($email)) {
            return redirect()->route('login')->with('error', "We could not get your email address from $provider. Please make your email public on GitHub or register manually.");
        }
        try {
            // Find existing user or create new social user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'avatar' => $socialUser->getAvatar(),
                    // Dynamic Column Assignment (e.g., google_id, github_id)
                    $provider . '_id' => $socialUser->getId(),
                ]
            );

            // User exists connect social id
            if (empty($user->{$provider . '_id'})) {
                $user->update([
                    $provider . '_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar() ?? $user->avatar, // Update avatar if provider has one, otherwise keep existing
                ]);
            }

            // Login the user
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Login with ' . ucfirst($provider) . ' Successful');
        } catch (\Exception $e) {
            dd('Database Error: ' . $e->getMessage());
        }
    }
}
