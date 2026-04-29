@extends('layouts.auth')

@section('content')
    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 24px; background-color: var(--bg-card);">
        <div class="row g-0">

            <div class="col-12 col-lg-5 p-5 d-flex flex-column justify-content-center">

                <div class="auth-toggle mb-4">
                    <button type="button" class="auth-tab active" data-tab="login">Log In</button>
                    <button type="button" class="auth-tab" data-tab="register">Sign Up</button>
                </div>

                {{-- ===== LOGIN PANEL ===== --}}
                <div id="panel-login" class="auth-panel active">
                    <h2 class="fw-bold mb-1">Welcome back</h2>
                    <p class="mb-4 small">Enter your details to access your account.</p>



                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@company.com"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="mb-4">
                            <a href="{{ route('forgot.password') }}" class="small text-decoration-none"
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

                {{-- ===== REGISTER PANEL ===== --}}
                <div id="panel-register" class="auth-panel">
                    <h2 class="fw-bold mb-1">Register Account</h2>
                    <p class="mb-4 small">Join teams while collaborating smarter.</p>



                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_form" value="register">
                        <div class="mb-3">
                            <label class="form-label small">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@company.com"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Create password"
                                required>
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Repeat password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-3">Create Account</button>
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

            </div>

            <div class="col-lg-7 d-none d-lg-block position-relative" style="min-height: 600px; background: #000;">
                <div class="slideshow">
                    <div class="slide"></div>
                    <div class="slide"></div>
                    <div class="slide"></div>
                </div>
                <div class="position-absolute bottom-0 start-0 p-5 text-white"
                    style="z-index: 10; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);">
                    <h3 class="fw-bold" id="slide-heading">Collaborate seamlessly.</h3>
                    <p class="opacity-75" id="slide-sub">Manage tasks, track progress, and hit your deadlines in style.</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Tab switching
        const tabs = document.querySelectorAll('.auth-tab');
        const panels = document.querySelectorAll('.auth-panel');

        function switchTab(tabName) {
            tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === tabName));
            panels.forEach(p => p.classList.toggle('active', p.id === 'panel-' + tabName));
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', () => switchTab(tab.dataset.tab));
        });

        // If there were register errors, stay on register tab
        @if (old('_form') === 'register' || $errors->hasAny(['name', 'password', 'password_confirmation']) && old('_form') === 'register')
            switchTab('register');
            notyf.error('Please fix the errors below.');
        @endif

        @error('LoginError')
            notyf.error('{{ $message }}');
        @enderror

                    // Also support ?tab=register query param (for external links)
                    const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'register') {
            switchTab('register');
        }
    </script>
@endsection