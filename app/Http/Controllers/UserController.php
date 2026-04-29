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
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function register(Request $request)
    {
        // Form input retrieval
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:6',
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

        // Redirect to email notification page
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
            if ($provider == 'google') {
                return redirect()->route('login')->with('error', 'Login with ' . ucfirst($provider) . ' failed. Please try again.');
            } elseif ($provider == 'github') {
                return redirect()->route('profile')->with('error', 'Connecting GitHub failed. Please try again.');
            }
        }
        if ($provider == 'google') {
            // Check if email is provided for google users (meant for github users but redundant now so fuck you)
            $email = $socialUser->getEmail();
            if (empty($email)) {
                return redirect()->route('login')->with('error', "We could not get your email address from $provider. Please make your email public on Google or register manually.");
            }
        }

        try {
            if ($provider == 'google') {

                // Find existing user or create new social user
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(24)),
                        'avatar' => $socialUser->getAvatar(),
                        $provider . '_id' => $socialUser->getId(),
                    ]
                );

                // User exists — connect social id
                if (empty($user->{$provider . '_id'})) {
                    $user->update([
                        $provider . '_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar() ?? $user->avatar,
                    ]);
                }

                Auth::login($user);

                return redirect()->route('dashboard')->with('success', 'Login with Google Successful');

            } elseif ($provider == 'github') {

                // Link GitHub to the currently authenticated user
                $user = Auth::user();

                // Dont Believe in clean code switch case nah if-else family trees
                if (!$user) {
                    return redirect()->route('login')->with('error', 'You must be logged in to connect GitHub.');
                } elseif ($user->github_id) {
                    return redirect()->route('profile')->with('error', 'GitHub account is already connected!');
                } elseif (User::where('github_id', $socialUser->getId())->first()) {
                    return redirect()->route('profile')->with('error', 'GitHub account is already connected to another account!');
                }

                $user->update([
                    'github_id' => $socialUser->getId(),
                ]);

                return redirect()->route('profile')->with('success', 'GitHub account connected successfully!');
            }
        } catch (\Exception $e) {
            return redirect()->route('profile')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function DisconnectGithub()
    {
        $user = Auth::user();
        if ($user->github_id) {
            $user->update([
                'github_id' => null,
            ]);
            return redirect()->route('profile')->with('success', 'GitHub account disconnected successfully!');
        }
        return redirect()->route('profile')->with('github_error', 'GitHub account is not connected!');
    }

    public function UpdateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'full_name' => 'nullable|string|max:255|min:6',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'avatar_base64' => 'nullable|string',
            ]);

            $updateData = [];

            if ($request->filled('full_name')) {
                $updateData['name'] = $request->full_name;
            }

            // Optimise Storage — handle cropped base64 avatar or direct file upload
            if ($request->filled('avatar_base64')) {
                // Decode base64 image from the crop modal
                $base64 = $request->input('avatar_base64');
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
                $imageData = base64_decode($imageData);

                $filename = 'avatars/' . uniqid('avatar_', true) . '.jpg';

                if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                    Storage::disk('public')->delete($user->avatar);
                }

                Storage::disk('public')->put($filename, $imageData);
                $updateData['avatar'] = $filename;

            } elseif ($request->hasFile('avatar')) {
                // Check if user has an existing avatar in storage and not a url
                if ($user->avatar && !str_starts_with($user->avatar, 'http')) {                                                                                     
                    // Optimise Storage — delete old avatar
                    Storage::disk('public')->delete($user->avatar);
                }

                $path = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = $path;
            }

            if (!empty($updateData)) {
                $user->update($updateData);
            }

            return redirect()->route('profile')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('profile')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
