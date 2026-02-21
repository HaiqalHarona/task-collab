<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskCollab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: var(--bg-card);
            border-right: 1px solid var(--border);
        }
        .nav-link.active {
            background-color: var(--primary) !important;
            color: #fff !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark d-md-none p-3" style="background-color: var(--bg-card); border-bottom: 1px solid var(--border);">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold" style="color: var(--primary);">TaskCollab</span>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse offcanvas-md offcanvas-start" id="sidebarMenu">
                <div class="offcanvas-header border-bottom border-secondary">
                    <h5 class="offcanvas-title" style="color: var(--primary);">TaskCollab</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>

                <div class="offcanvas-body d-flex flex-column p-3 pt-4 h-100">
                    <a href="#" class="d-none d-md-block text-decoration-none fs-4 mb-4 fw-bold p-2" style="color: var(--primary);">
                        <i class="bi bi-layers-fill me-2"></i>TaskCollab
                    </a>

                    <ul class="nav nav-pills flex-column mb-auto gap-1">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link active rounded-3">
                                <i class="bi bi-grid-1x2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('workspace') }}" class="nav-link rounded-3">
                                <i class="bi bi-briefcase me-2"></i> Workspaces
                            </a>
                        </li>
                        <li class="mt-2">
                            <a href="#" class="nav-link rounded-3 text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createWorkspaceModal">
                                <i class="bi bi-plus-circle me-2"></i> New Workspace
                            </a>
                        </li>
                    </ul>

                    <hr class="border-secondary">

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle p-2 rounded-3" style="color: var(--text-main);" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=User&background=8b5cf6&color=fff" alt="" width="32" height="32" class="rounded-circle me-2">
                            <strong>{{ Auth::user()->name ?? 'User Name' }}</strong>
                        </a>
                        <ul class="dropdown-menu shadow w-100">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <div class="modal fade" id="createWorkspaceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Workspace</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <div class="mb-3">
                            <label class="form-label small">Workspace Name</label>
                            <input type="text" class="form-control" placeholder="e.g. Marketing Team">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Description (Optional)</label>
                            <textarea class="form-control" rows="3" placeholder="What is this workspace for?"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create Workspace</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>