@extends('layouts.auth')

@section('content')
    <div class="card border-0 shadow-lg mx-auto overflow-hidden"
        style="border-radius: 24px; background-color: var(--bg-card); max-width: 500px;">
        <div class="p-5 d-flex flex-column justify-content-center">

            <h2 class="fw-bold mb-2">Forgot Password</h2>
            <p class="mb-4 small" style="color: var(--text-muted);">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset
                link.
            </p>

            @if (session('status'))
                <div class="alert alert-success small mb-4 text-dark">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label small">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@company.com"
                        value="{{ old('email') }}" required autofocus>

                    @error('email')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-3 mb-4">Email Password Reset Link</button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="small text-decoration-none" style="color: var(--text-muted);">
                        <i class="bi bi-arrow-left me-1"></i> Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
