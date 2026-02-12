@extends('layouts.auth')

@section('content')
    <h2 class="text-center mb-4">Create Account</h2>

    <form action="{{ route('register') }}" method="POST" id="registerForm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" id="password" name="password"
                class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            <div id="password-feedback" class="form-text mt-1 fw-bold"></div>
        </div>

        <button type="submit" id="submitBtn" class="btn btn-dark w-100">Register</button>
    </form>

    <div class="mt-3 pt-3 border-top text-center">
        <p>Or Register with:</p>
        {{-- Social buttons here --}}
    </div>

    <p class="text-center mt-3">
        Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a>
    </p>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#password, #password_confirmation').on('keyup', function() {
                let password = $('#password').val();
                let confirm = $('#password_confirmation').val();
                let feedback = $('#password-feedback');
                let btn = $('#submitBtn');

                // Reset if empty
                if (confirm.length === 0) {
                    feedback.text('');
                    $('#password_confirmation').removeClass('is-valid is-invalid');
                    return;
                }

                if (password === confirm) {
                    feedback.text('Passwords match!').removeClass('text-danger').addClass('text-success');
                    $('#password_confirmation').removeClass('is-invalid').addClass('is-valid');
                    btn.prop('disabled', false);
                } else {
                    feedback.text('Passwords do not match.').removeClass('text-success').addClass(
                        'text-danger');
                    $('#password_confirmation').removeClass('is-valid').addClass('is-invalid');
                    btn.prop('disabled', true);
                }
            });
        });
    </script>
@endpush
