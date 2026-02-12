<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{

    public function register(Request $request)
    {
        // Form input retrieval
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed'

        ]);

        // Data Insert
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Start the session
        Auth::login($user);

        // Redirect to dashboard
        return redirect()->route('dashboard')->with('success', 'Account Created Successfully');
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
            return redirect()->route('login')->with('error', 'Login with ' . ucfirst($provider) . ' failed. Please try again.');
        }
        // Find existing user or create new social user
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
                // Dynamic Column Assignment (e.g., google_id, github_id)
                $provider . '_id' => $socialUser->getId(),
            ]
        );
        // User exists connect social id
        if (empty($user->{$provider . '_id'})) {
            $user->update([
                $provider . '_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        }

        // Login the user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Login with ' . ucfirst($provider) . ' Successful');

    }
}
