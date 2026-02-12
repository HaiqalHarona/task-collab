@extends('layouts.auth')

@section('content')
    <h2 class="text-center mb-4">Login</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Capture the manual login error from Controller --}}
    @error('loginError')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-dark w-100">Login</button>
    </form>

    <div class="mt-3 pt-3 border-top text-center">
        <p>Or login with:</p>
        {{-- Social buttons here --}}
    </div>

    <p class="text-center mt-3">
        No account? <a href="{{ route('register') }}" class="text-decoration-none">Register here</a>
    </p>
@endsection