@extends('layouts.auth')

@section('content')
    <h2>Create Account</h2>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>
    </form>

    <div style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px;">
        <p style="text-align: center;">Or Register with:</p>
        {{-- <a href="{{ route('social.redirect', 'google') }}" class="social-btn google">Login with Google</a>
        <a href="{{ route('social.redirect', 'github') }}" class="social-btn github">Login with GitHub</a> --}}
    </div>

    <p style="text-align: center; margin-top: 15px;">
        Already have an account? <a href="{{ route('login') }}">Login here</a>
    </p>
@endsection
