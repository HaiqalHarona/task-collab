@extends('layouts.auth')

@section('content')
    <div class="container d-flex flex-column justify-content-center align-items-center">

        <div
            style="max-width: 450px; width: 100%; background: var(--bg-card); padding: 3rem; border-radius: 16px; border: 1px solid var(--border);
                                    border-top: 2px solid var(--primary); box-shadow: 0 0 0 1px var(--border), 0 5px 10px rgba(0,0,0,0.6), 0 0 1px rgba(139,92,246,0.12);">

            <div class="text-center">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center"
                    style="width: 64px; height: 64px; border-radius: 14px; background: rgba(139,92,246,0.15); border: 1px solid rgba(139,92,246,0.35); font-size: 1.75rem; color: var(--primary);">
                    <i class="bi bi-envelope-check-fill"></i>
                </div>

                <h2 class="fw-bold mb-3">Check Your Inbox</h2>
                <p class="text-muted mb-4">
                    We've sent a verification link to the email address you provided. Please confirm it to activate your
                    account.
                </p>
            </div>



            <div class="d-grid gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 py-3">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100 py-3">
                        Return to Login
                    </button>
                </form>
            </div>
        </div>

        <div class="auth-footer mt-4">
            &copy; {{ date('Y') }} {{ config('app.name') }}.
        </div>

    </div>

    <script>
        @if (session('message'))
            notyf.success('A new verification link has been sent.');
        @endif
    </script>
@endsection