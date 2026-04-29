@extends('layouts.app')

@section('content')
    <style>
        .fw-bold {
            color: var(--text-main);
        }
    </style>
    <div class="d-flex justify-content-between align-items-center mb-4" style="margin-bottom: 5px;">
        <h2 class="fw-bold m-0">Dashboard</h2>
        <span class="text-muted">
            {{ now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('l, F j, Y') }}
        </span>
    </div>

    <div class="row g-4 mb-5 justify-content-center">
        <livewire:dashboard-display />
    </div>


    <h5 class="fw-bold mb-3">Recent Tasks</h5>
    <livewire:dashboard-recent />
@endsection