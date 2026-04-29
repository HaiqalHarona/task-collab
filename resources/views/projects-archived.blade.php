@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold m-0">Archived Projects</h2>
            <p class="text-muted m-0 small">Archived Projects will be deleted after 30 days if not restored.</p>
        </div>
        <a href="{{ route('projects') }}" class="btn btn-outline-secondary d-none d-md-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to Projects
        </a>
    </div>

    <livewire:archived-display />

@endsection