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
            'password' => 'required|string|min:6'

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
        
        return redirect()->route('login')->with('error', 'Invalid Login Credentials');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout Successful');
    }
}