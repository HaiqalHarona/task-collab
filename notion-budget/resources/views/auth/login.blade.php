@extends('layouts.auth')

@section('content')
    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 24px; background-color: var(--bg-card);">
        <div class="row g-0">

            <div class="col-12 col-lg-5 p-5 d-flex flex-column justify-content-center">

                <div class="auth-toggle">
                    <a href="{{ route('login') }}" class="active">Log In</a>
                    <a href="{{ route('register') }}">Sign Up</a>
                </div>

                <h2 class="fw-bold mb-1">Welcome back</h2>
                <p class=" mb-4 small">Enter your details to access your account.</p>

                @if (session('success'))
                    <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
                @endif
                @error('LoginError')
                    <div class="alert alert-danger py-2 small">{{ $message }}</div>
                @enderror

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small ">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="name@company.com"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small ">Password</label>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="mb-4"> <a href="{{ route('forgot.password') }}" class="small text-decoration-none"
                            style="color: var(--primary);">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3">Log In</button>
                </form>

                <div class="d-flex align-items-center my-4">
                    <hr class="flex-grow-1 border-secondary opacity-25">
                    <span class="mx-3 small">OR</span>
                    <hr class="flex-grow-1 border-secondary opacity-25">
                </div>

                <a href="{{ route('social.redirect', 'google') }}"
                    class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2"
                    style="border-color: var(--border); color: var(--text-main);">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="20" alt="Google">
                    Continue with Google
                </a>
            </div>

            <div class="col-lg-7 d-none d-lg-block position-relative" style="min-height: 600px; background: #000;">
                <div class="slideshow">
                    <div class="slide"></div>
                    <div class="slide"></div>
                    <div class="slide"></div>
                </div>
                <div class="position-absolute bottom-0 start-0 p-5 text-white"
                    style="z-index: 10; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);">
                    <h3 class="fw-bold">Collaborate seamlessly.</h3>
                    <p class="opacity-75">Manage tasks, track progress, and hit your deadlines in style.</p>
                </div>
            </div>

        </div>
    </div>
@endsection
