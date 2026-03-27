<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="{{ asset('css/applayout.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    @livewireStyles
    @vite(['resources/js/echo.js'])
    @stack('styles')


</head>

<body>

    {{-- ── Mobile Bottom Nav (hidden on md+) ── --}}
    <nav class="mobile-bottom-nav d-md-none">
        <a href="{{ route('dashboard') }}"
            class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('projects') }}" class="mobile-nav-item {{ request()->routeIs('projects') ? 'active' : '' }}">
            <i class="bi bi-briefcase"></i>
            <span>Projects</span>
        </a>
        <a href="{{ route('profile') }}" class="mobile-nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    </nav>

    {{-- ── Floating Action Button (mobile only) ── --}}
    <a href="#" class="fab d-md-none" data-bs-toggle="modal" data-bs-target="#createProjectModal"
        aria-label="New Project">
        <i class="bi bi-plus-lg"></i>
    </a>

    <div class="container-fluid">
        <div class="row">
            @if(!View::hasSection('no-sidebar'))
            <div class="col-md-3 col-lg-3 d-md-block sidebar collapse offcanvas-md offcanvas-start" id="sidebarMenu">
                <div class="offcanvas-header border-bottom border-secondary">
                    <h5 class="offcanvas-title" style="color: var(--primary);">{{ config('app.name') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>

                <div class="offcanvas-body d-flex flex-column p-3 pt-4 h-100">
                    <a href="{{ route('dashboard') }}"
                        class="d-none d-md-block text-decoration-none fs-4 mb-4 fw-bold p-2"
                        style="color: var(--primary);">
                        {{ config('app.name') }}
                    </a>

                    <ul class="nav nav-pills flex-column mb-auto gap-1">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} rounded-3">
                                <i class="bi bi-grid-1x2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('projects') }}"
                                class="nav-link {{ request()->routeIs('projects') ? 'active' : '' }} rounded-3">
                                <i class="bi bi-briefcase me-2"></i> Projects
                            </a>
                        </li>
                        <li class="mt-2">
                            <a href="#" class="nav-link rounded-3 text-success d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#createProjectModal">
                                <i class="bi bi-plus-circle me-2"></i> New Project
                            </a>
                        </li>
                    </ul>

                    <hr class="border-secondary">

                    <div class="dropdown">
                        <a href="#" class="user-dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ empty(Auth::user()->avatar) ? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') . '&background=6366f1&color=fff' : (Str::startsWith(Auth::user()->avatar, ['http://', 'https://']) ? Auth::user()->avatar : Storage::url(Auth::user()->avatar)) }}"
                                alt="{{ Auth::user()->name ?? 'User' }}" class="user-avatar-ring">
                            <span class="user-name-text">{{ Auth::user()->name ?? 'User Name' }}</span>
                            <i class="bi bi-chevron-up user-caret"></i>
                        </a>
                        <ul class="dropdown-menu user-dropdown-menu">
                            <li class="user-info-header">
                                <div class="fw-semibold" style="font-size:.875rem; color: white">
                                    {{ Auth::user()->name ?? 'User Name' }}
                                </div>
                                <div class="user-email" style="color: white">{{ Auth::user()->email ?? '' }}</div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="bi bi-person-circle"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear"></i> Settings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger w-100 border-0 bg-transparent text-start"
                                        type="submit">
                                        <i class="bi bi-box-arrow-right"></i> Sign out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <main class="{{ View::hasSection('no-sidebar') ? 'col-12' : 'col-md-9 ms-sm-auto col-lg-9' }} px-md-5 py-4 main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Create Project Modal -->
    <div class="modal fade" id="createProjectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Project</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('project.create') }}" method="POST" enctype="multipart/form-data" id="createProjectForm">
                    @csrf
                    <div class="modal-body">
                        {{-- Project Name --}}
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Project Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Marketing Team" required>
                            @error('name')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Icon Upload --}}
                        <div class="mb-4">
                            <label class="form-label small fw-semibold d-flex align-items-center gap-2">
                                Project Icon
                                <span class="badge rounded-pill text-muted fw-normal"
                                    style="background:rgba(255,255,255,.08);font-size:.7rem;padding:2px 8px;">optional</span>
                            </label>

                            {{-- Drop zone --}}
                            <div id="projIconDropzone"
                                style="border:2px dashed rgba(99,102,241,.45);border-radius:14px;padding:20px 16px;
                                       background:rgba(99,102,241,.06);cursor:pointer;transition:border-color .2s,background .2s;
                                       display:flex;align-items:center;gap:16px;">

                                {{-- Preview circle --}}
                                <div id="projIconPreviewWrap"
                                    style="width:64px;height:64px;border-radius:12px;overflow:hidden;flex-shrink:0;
                                           background:rgba(99,102,241,.18);border:1px solid rgba(99,102,241,.35);
                                           display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-image fs-3 text-muted" id="projIconFallback"></i>
                                    <img id="projIconPreviewImg" src="" alt="" class="d-none"
                                        style="width:100%;height:100%;object-fit:cover;">
                                </div>

                                {{-- Text + click hint --}}
                                <div>
                                    <div class="fw-semibold small text-white" id="projIconLabel">Choose an image</div>
                                    <div class="text-muted" style="font-size:.72rem;margin-top:3px;">
                                        PNG, JPG or GIF &middot; max 1&nbsp;MB
                                    </div>
                                    <button type="button" class="btn btn-outline-light btn-sm mt-2"
                                        style="font-size:.72rem;padding:3px 12px;border-radius:8px;"
                                        onclick="document.getElementById('projIconFileInput').click()">
                                        <i class="bi bi-upload me-1"></i>Browse
                                    </button>
                                </div>
                            </div>

                            {{-- Hidden real file input + hidden base64 --}}
                            <input type="file" id="projIconFileInput" class="d-none"
                                accept="image/png,image/jpeg,image/jpg,image/gif">
                            <input type="hidden" name="icon_base64" id="projIconBase64">
                        </div>

                        {{-- Colour Picker --}}
                        <div class="mb-4">
                            <label class="form-label small fw-semibold d-flex align-items-center gap-2">
                                Project Colour
                                <span class="badge rounded-pill text-muted fw-normal"
                                    style="background:rgba(255,255,255,.08);font-size:.7rem;padding:2px 8px;">optional</span>
                            </label>

                            {{-- Live preview band --}}
                            <div id="projColorPreview"
                                style="height:36px;border-radius:10px;margin-bottom:10px;
                                       background:linear-gradient(135deg,#6c63ff 0%,#4f46e5 100%);
                                       transition:background .3s;"></div>

                            {{-- Swatches --}}
                            <div class="d-flex flex-wrap gap-2" id="projColorSwatches">
                                @foreach([
    '#6c63ff',
    '#4f46e5',
    '#06b6d4',
    '#10b981',
    '#f59e0b',
    '#ef4444',
    '#ec4899',
    '#8b5cf6',
    '#14b8a6',
    '#f97316'
] as $swatch)
                                <button type="button"
                                    class="proj-swatch {{ $swatch === '#6c63ff' ? 'proj-swatch-active' : '' }}"
                                    data-color="{{ $swatch }}"
                                    style="width:30px;height:30px;border-radius:8px;border:2px solid transparent;
                                           background:{{ $swatch }};cursor:pointer;transition:transform .15s,border-color .15s;
                                           flex-shrink:0;padding:0;{{ $swatch === '#6c63ff' ? 'border-color:#fff;transform:scale(1.18);' : '' }}"
                                    title="{{ $swatch }}">
                                </button>
                                @endforeach

                                {{-- Custom colour input --}}
                                <label title="Custom colour"
                                    style="width:30px;height:30px;border-radius:8px;cursor:pointer;overflow:hidden;
                                           border:2px solid rgba(255,255,255,.25);flex-shrink:0;display:flex;
                                           align-items:center;justify-content:center;background:rgba(255,255,255,.08);">
                                    <i class="bi bi-palette text-white" style="font-size:.8rem;pointer-events:none;"></i>
                                    <input type="color" id="projColorCustom" value="#6c63ff"
                                        style="opacity:0;width:0;height:0;position:absolute;">
                                </label>
                            </div>

                            <input type="hidden" name="color" id="projColorHidden" value="#6c63ff">
                        </div>

                        {{-- Description --}}
                        <div class="mb-1">
                            <label class="form-label small fw-semibold d-flex align-items-center gap-2">
                                Description
                                <span class="badge rounded-pill text-muted fw-normal"
                                    style="background:rgba(255,255,255,.08);font-size:.7rem;padding:2px 8px;">optional</span>
                            </label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="What is this project for?" style="resize:none;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Crop Modal for project icon --}}
    <div class="modal fade" id="projIconCropModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white" style="background-color:var(--card-bg,#1e1e2e);">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Crop Project Icon</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <div style="max-height:380px;width:100%;background:#000;">
                        <img id="projIconCropImg" src="" style="max-width:100%;display:block;">
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-light" id="projIconCropCancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="projIconCropSave">
                        <i class="bi bi-crop me-1"></i>Crop &amp; Use
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Project</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('project.update') }}" method="POST" enctype="multipart/form-data" id="editProjectForm">
                    @csrf
                    <input type="hidden" name="project_id" id="editProjectId">

                    <div class="modal-body">
                        {{-- Project Name --}}
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Project Name</label>
                            <input type="text" name="name" id="editProjectName" class="form-control"
                                placeholder="e.g. Marketing Team" required>
                        </div>

                        {{-- Icon Upload --}}
                        <div class="mb-4">
                            <label class="form-label small fw-semibold d-flex align-items-center gap-2">
                                Project Icon
                                <span class="badge rounded-pill text-muted fw-normal"
                                    style="background:rgba(255,255,255,.08);font-size:.7rem;padding:2px 8px;">optional</span>
                            </label>

                            <div id="editProjIconDropzone"
                                style="border:2px dashed rgba(99,102,241,.45);border-radius:14px;padding:20px 16px;
                                       background:rgba(99,102,241,.06);cursor:pointer;transition:border-color .2s,background .2s;
                                       display:flex;align-items:center;gap:16px;">

                                <div id="editProjIconPreviewWrap"
                                    style="width:64px;height:64px;border-radius:12px;overflow:hidden;flex-shrink:0;
                                           background:rgba(99,102,241,.18);border:1px solid rgba(99,102,241,.35);
                                           display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-image fs-3 text-muted" id="editProjIconFallback"></i>
                                    <img id="editProjIconPreviewImg" src="" alt="" class="d-none"
                                        style="width:100%;height:100%;object-fit:cover;">
                                </div>

                                <div>
                                    <div class="fw-semibold small text-white" id="editProjIconLabel">Current icon</div>
                                    <div class="text-muted" style="font-size:.72rem;margin-top:3px;">
                                        PNG, JPG or GIF &middot; max 1&nbsp;MB
                                    </div>
                                    <button type="button" class="btn btn-outline-light btn-sm mt-2"
                                        style="font-size:.72rem;padding:3px 12px;border-radius:8px;"
                                        onclick="document.getElementById('editProjIconFileInput').click()">
                                        <i class="bi bi-upload me-1"></i>Change
                                    </button>
                                </div>
                            </div>

                            <input type="file" id="editProjIconFileInput" class="d-none"
                                accept="image/png,image/jpeg,image/jpg,image/gif">
                            <input type="hidden" name="icon_base64" id="editProjIconBase64">
                        </div>

                        {{-- Colour Picker --}}
                        <div class="mb-4">
                            <label class="form-label small fw-semibold d-flex align-items-center gap-2">
                                Project Colour
                                <span class="badge rounded-pill text-muted fw-normal"
                                    style="background:rgba(255,255,255,.08);font-size:.7rem;padding:2px 8px;">optional</span>
                            </label>

                            <div id="editProjColorPreview"
                                style="height:36px;border-radius:10px;margin-bottom:10px;
                                       background:linear-gradient(135deg,#6c63ff 0%,#4f46e5 100%);
                                       transition:background .3s;"></div>

                            <div class="d-flex flex-wrap gap-2" id="editProjColorSwatches">
                                @foreach(['#6c63ff', '#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#8b5cf6', '#14b8a6', '#f97316'] as $swatch)
                                <button type="button"
                                    class="edit-proj-swatch"
                                    data-color="{{ $swatch }}"
                                    style="width:30px;height:30px;border-radius:8px;border:2px solid transparent;
                                           background:{{ $swatch }};cursor:pointer;transition:transform .15s,border-color .15s;
                                           flex-shrink:0;padding:0;"
                                    title="{{ $swatch }}">
                                </button>
                                @endforeach

                                <label title="Custom colour"
                                    style="width:30px;height:30px;border-radius:8px;cursor:pointer;overflow:hidden;
                                           border:2px solid rgba(255,255,255,.25);flex-shrink:0;display:flex;
                                           align-items:center;justify-content:center;background:rgba(255,255,255,.08);">
                                    <i class="bi bi-palette text-white" style="font-size:.8rem;pointer-events:none;"></i>
                                    <input type="color" id="editProjColorCustom" value="#6c63ff"
                                        style="opacity:0;width:0;height:0;position:absolute;">
                                </label>
                            </div>

                            <input type="hidden" name="color" id="editProjColorHidden" value="#6c63ff">
                        </div>

                        {{-- Description --}}
                        <div class="mb-1">
                            <label class="form-label small fw-semibold d-flex align-items-center gap-2">
                                Description
                                <span class="badge rounded-pill text-muted fw-normal"
                                    style="background:rgba(255,255,255,.08);font-size:.7rem;padding:2px 8px;">optional</span>
                            </label>
                            <textarea name="description" id="editProjectDescription" class="form-control" rows="3"
                                placeholder="What is this project for?" style="resize:none;"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-0 d-flex justify-content-between">
                        <button type="submit" class="btn btn-outline-danger btn-sm"
                            formaction="{{ route('project.delete') }}"
                            style="border-radius:8px;"
                            onclick="return confirm('Are you sure you want to delete this project? It will not delete but be archived.')">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            duration: 4000,
            position: { x: 'right', y: 'top' },
            ripple: true,
            dismissible: true,
        });
        @if(session('success')) notyf.success(@json(session('success'))); @endif
        @if(session('error'))   notyf.error(@json(session('error'))); @endif
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="{{ asset('js/applayout.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    @livewireScripts
    @stack('scripts')

</body>

</html>
