@extends('layouts.auth')

@section('content')
    <div class="card p-4 text-center">
        <h2>Verify Your Email</h2>

        @if (session('success'))
            <div class="alert alert-success mt-3 mb-3" role="alert">
                {{ session('success') }}
            </div>
        @else
            <p class="mt-3 mb-3">Before getting started, could you verify your email address by clicking on the link we just
                emailed to you?</p>
        @endif

        @if (session('message'))
            <div class="alert alert-info mt-3 mb-3" role="alert">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-dark w-100">
                Resend Verification Email
            </button>
        </form>
    </div>
@endsection
