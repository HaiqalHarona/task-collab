@extends('layouts.auth')

@section('content')
    <h2>Login</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px;">
        <p style="text-align: center;">Or login with:</p>
        {{-- <a href="{{ route('social.redirect', 'google') }}" class="social-btn google">Login with Google</a>
        <a href="{{ route('social.redirect', 'github') }}" class="social-btn github">Login with GitHub</a> --}}
    </div>

    <p style="text-align: center; margin-top: 15px;">
        No account? <a href="{{ route('register') }}">Register here</a>
    </p>
@endsection
