@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Dashboard</h2>
        <span class="text-muted">{{ now()->format('l, F j, Y') }}</span>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card p-4 h-100 rounded-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-muted m-0">Pending Tasks</h6>
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <h2 class="fw-bold m-0">12</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 h-100 rounded-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-muted m-0">Completed Tasks</h6>
                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                </div>
                <h2 class="fw-bold m-0">45</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 h-100 rounded-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-muted m-0">Active Workspaces</h6>
                    <i class="bi bi-briefcase fs-4" style="color: var(--primary);"></i>
                </div>
                <h2 class="fw-bold m-0">3</h2>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3">Recent Tasks</h5>
    <div class="card rounded-4 p-0 overflow-hidden">
        <ul class="list-group list-group-flush" style="background-color: transparent;">
            <li class="list-group-item d-flex justify-content-between align-items-center p-3"
                style="background-color: transparent; border-color: var(--border);">
                <div class="d-flex align-items-center gap-3">
                    <input class="form-check-input mt-0" type="checkbox" style="width: 1.2rem; height: 1.2rem;">
                    <div>
                        <h6 class="m-0 mb-1" style="color: var(--text-main);">Update Landing Page Copy</h6>
                        <small class="text-muted">Marketing Workspace &bull; Due Today</small>
                    </div>
                </div>
                <span class="badge bg-danger rounded-pill">High Priority</span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center p-3"
                style="background-color: transparent; border-color: var(--border);">
                <div class="d-flex align-items-center gap-3">
                    <input class="form-check-input mt-0" type="checkbox" style="width: 1.2rem; height: 1.2rem;">
                    <div>
                        <h6 class="m-0 mb-1" style="color: var(--text-main);">Fix Navigation Bug</h6>
                        <small class="text-muted">Engineering Workspace &bull; Due Tomorrow</small>
                    </div>
                </div>
                <span class="badge bg-warning text-dark rounded-pill">Medium Priority</span>
            </li>
        </ul>
    </div>
@endsection
