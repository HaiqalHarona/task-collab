@extends('layouts.app')

@section('content')
    <h2 class="fw-bold mb-4">Profile Settings</h2>

    <div class="row">
        <div class="col-lg-8">
            <div class="card p-4 rounded-4 mb-4">
                <h5 class="fw-bold mb-4">Personal Information</h5>
                <form action="#">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small">Full Name</label>
                            <input type="text" class="form-control" value="John Doe">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Email Address</label>
                            <input type="email" class="form-control" value="john@example.com">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-2">Save Changes</button>
                </form>
            </div>

            <div class="card p-4 rounded-4">
                <h5 class="fw-bold mb-1">Connected Accounts</h5>
                <p class="text-muted small mb-4">Connect your social accounts to log in faster.</p>

                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3" style="border: 1px solid var(--border);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-github fs-2"></i>
                        <div>
                            <h6 class="m-0 fw-bold">GitHub</h6>
                            <small class="text-muted">Not connected</small>
                        </div>
                    </div>
                    <a href="#" class="btn btn-outline-light d-flex align-items-center gap-2">
                        Connect
                    </a>
                </div>

                <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="border: 1px solid var(--border);">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="32" alt="Google">
                        <div>
                            <h6 class="m-0 fw-bold">Google</h6>
                            <small class="text-success">Connected as john@example.com</small>
                        </div>
                    </div>
                    <button class="btn btn-outline-danger d-flex align-items-center gap-2">
                        Disconnect
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection