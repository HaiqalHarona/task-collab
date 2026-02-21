<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthRoutes
{
    public function profile()
    {
        return view('profile');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function workspace()
    {
        return view('workspace');
    }
}
