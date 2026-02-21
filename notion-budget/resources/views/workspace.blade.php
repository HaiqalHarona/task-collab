@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold m-0">Project Alpha</h2>
            <p class="text-muted m-0 small">Managed by Engineering Team</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-light d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#addMemberModal">
                <i class="bi bi-person-plus"></i> Add Member
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#addTaskModal">
                <i class="bi bi-plus-lg"></i> New Task
            </button>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mb-4">
        <span class="small text-muted me-2">Team:</span>
        <img src="https://ui-avatars.com/api/?name=JD&background=8b5cf6&color=fff" class="rounded-circle border border-dark"
            width="32">
        <img src="https://ui-avatars.com/api/?name=AS&background=10b981&color=fff" class="rounded-circle border border-dark"
            width="32" style="margin-left:-10px;">
        <img src="https://ui-avatars.com/api/?name=RK&background=f59e0b&color=fff" class="rounded-circle border border-dark"
            width="32" style="margin-left:-10px;">
    </div>

    <div class="card rounded-4 p-0 overflow-hidden">
        <div class="card-header border-bottom p-3 d-flex justify-content-between align-items-center"
            style="background-color: var(--bg-card);">
            <h6 class="m-0 fw-bold">All Tasks</h6>
        </div>
        <ul class="list-group list-group-flush" style="background-color: transparent;">
            <li class="list-group-item p-3" style="background-color: transparent; border-color: var(--border);">
                <div class="row align-items-center">
                    <div class="col-md-6 d-flex align-items-center gap-3 mb-2 mb-md-0">
                        <input class="form-check-input mt-0" type="checkbox" style="width: 1.2rem; height: 1.2rem;">
                        <div>
                            <h6 class="m-0" style="color: var(--text-main);">Database Schema Design</h6>
                            <small class="text-muted">Assigned to: Alice Smith</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-md-center mb-2 mb-md-0">
                        <span class="badge bg-primary rounded-pill">In Progress</span>
                    </div>
                    <div class="col-md-2 text-md-center mb-2 mb-md-0">
                        <span class="badge bg-danger rounded-pill">High Priority</span>
                    </div>
                    <div class="col-md-2 text-md-end text-muted small">
                        Oct 24, 2026
                    </div>
                </div>
            </li>

            <li class="list-group-item p-3" style="background-color: transparent; border-color: var(--border);">
                <div class="row align-items-center">
                    <div class="col-md-6 d-flex align-items-center gap-3 mb-2 mb-md-0">
                        <input class="form-check-input mt-0" type="checkbox" style="width: 1.2rem; height: 1.2rem;">
                        <div>
                            <h6 class="m-0" style="color: var(--text-main);">Setup Authentication via GitHub</h6>
                            <small class="text-muted">Assigned to: Unassigned</small>
                        </div>
                    </div>
                    <div class="col-md-2 text-md-center mb-2 mb-md-0">
                        <span class="badge bg-secondary rounded-pill">To Do</span>
                    </div>
                    <div class="col-md-2 text-md-center mb-2 mb-md-0">
                        <span class="badge bg-info text-dark rounded-pill">Low Priority</span>
                    </div>
                    <div class="col-md-2 text-md-end text-muted small">
                        Oct 28, 2026
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <div class="mb-3">
                            <label class="form-label small">Task Title</label>
                            <input type="text" class="form-control" placeholder="What needs to be done?">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Description</label>
                            <textarea class="form-control" rows="4" placeholder="Add some details..."></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small">Assign To</label>
                                <select class="form-select">
                                    <option selected>Unassigned</option>
                                    <option value="1">John Doe</option>
                                    <option value="2">Alice Smith</option>
                                    <option value="3">Rahul Kumar</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Priority</label>
                                <select class="form-select">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Due Date</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Task</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addMemberModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Invite Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <div class="mb-3">
                            <label class="form-label small">Email Address</label>
                            <input type="email" class="form-control" placeholder="colleague@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Role</label>
                            <select class="form-select">
                                <option value="member" selected>Member (Can edit tasks)</option>
                                <option value="viewer">Viewer (Read-only)</option>
                                <option value="admin">Admin (Full access)</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Send Invite</button>
                </div>
            </div>
        </div>
    </div>
@endsection
