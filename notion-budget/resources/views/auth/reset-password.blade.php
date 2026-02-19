@extends('layouts.auth')

@section('content')
    <div class="card border-0 shadow-lg mx-auto overflow-hidden"
        style="border-radius: 24px; background-color: var(--bg-card); max-width: 500px;">
        <div class="p-5 d-flex flex-column justify-content-center">

            <h2 class="fw-bold mb-2">Reset Password</h2>
            <p class="mb-4 small" style="color: var(--text-muted);">
                Please enter your email and a new password below.
            </p>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label small">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@company.com"
                        value="{{ request()->email ?? old('email') }}" required autofocus>
                    @error('email')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    @error('password')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-3">Reset Password</button>
            </form>
        </div>
    </div>
@endsection
