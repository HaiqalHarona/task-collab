@extends('layouts.app')

@section('content')

    <style>
        .github-icon {
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }

        .github-icon.connected {
            color: #6e40c9;
            text-shadow: 0 0 12px rgba(110, 64, 201, 0.7), 0 0 24px rgba(110, 64, 201, 0.4);
        }

        .btn-github-connect {
            transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .btn-github-connect:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: #fff;
            border-color: #fff;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.15);
        }

        .btn-github-disconnect {
            transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
            border-color: #ffffffff;
            color: #ffffffff;
        }

        .btn-github-disconnect:hover {
            background-color: rgba(255, 0, 0, 1);
            color: #fff;
            border-color: #ffffffff;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.35);
        }
    </style>

    <h2 class="fw-bold mb-4">Profile Settings</h2>

    <div class="row">
        <div class="col-lg-8">
            <div class="card p-4 rounded-4 mb-4">
                <h5 class="fw-bold mb-4 text-white">Personal Information</h5>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Image Upload Section --}}
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="position-relative">
                            <img src="{{ empty(Auth::user()->avatar) ? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') . '&background=6366f1&color=fff' : (str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : Storage::url(Auth::user()->avatar)) }}"
                                alt="Avatar Preview" id="avatarPreview" class="rounded-circle"
                                style="width: 100px; height: 100px; object-fit: cover; border: 2px solid var(--border);">
                        </div>
                        <div>
                            <label for="avatarInput" class="btn btn-outline-light btn-sm mb-1">
                                <i class="bi bi-camera me-1"></i>Upload Image
                            </label>
                            <input type="file" id="avatarInput" class="d-none" accept="image/png, image/jpeg, image/jpg"
                                name="avatar">
                            <input type="hidden" name="avatar_base64" id="avatarBase64">
                            <div class="form-text text-muted" style="font-size: 0.75rem;">JPG, GIF or PNG. Max size of 2MB.
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small">Full Name</label>
                            <input type="text" class="form-control" name="full_name" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Email Address</label>
                            <div class="position-relative">

                                {{-- Input is now disabled and greyed out for everyone --}}
                                <input type="email"
                                    class="form-control bg-body-secondary text-muted @if(Auth::user()->google_id) pe-5 @endif"
                                    value="{{ Auth::user()->email }}" disabled>

                                @if(Auth::user()->google_id)
                                    <span title="Managed by Google — cannot be changed here" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        style="position:absolute; right:10px; top:50%; transform:translateY(-50%); line-height:1; cursor:default; pointer-events:auto;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                                            <path fill="#EA4335"
                                                d="M24 9.5c3.5 0 6.6 1.2 9 3.2l6.7-6.7C35.8 2.5 30.2 0 24 0 14.6 0 6.6 5.4 2.6 13.3l7.8 6C12.4 13 17.8 9.5 24 9.5z" />
                                            <path fill="#4285F4"
                                                d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4 6.9-10 6.9-17z" />
                                            <path fill="#FBBC05"
                                                d="M10.4 28.6A14.6 14.6 0 0 1 9.5 24c0-1.6.3-3.2.9-4.6l-7.8-6A23.9 23.9 0 0 0 0 24c0 3.9.9 7.5 2.6 10.7l7.8-6.1z" />
                                            <path fill="#34A853"
                                                d="M24 48c6.2 0 11.4-2 15.2-5.5l-7.5-5.8c-2 1.4-4.6 2.2-7.7 2.2-6.2 0-11.5-4.2-13.4-9.8l-7.8 6C6.6 42.6 14.6 48 24 48z" />
                                        </svg>
                                    </span>
                                @endif
                            </div>

                            {{-- 1/2/2026 Added the @else block here for standard users Fuck me for these bugs --}}
                            @if(Auth::user()->google_id)
                                <div class="form-text" style="font-size:.75rem; color: white !important;">
                                    <i class="bi bi-lock me-1"></i>Email is managed by Google and cannot be changed.
                                </div>
                            @else
                                <div class="form-text" style="font-size:.75rem; color: white !important;">
                                    <i class="bi bi-lock me-1"></i>Email address cannot be changed.
                                </div>
                            @endif

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Save</button>


                </form>
            </div>

            <div class="card p-4 rounded-4">
                <h5 class="fw-bold mb-1 text-white">Connected Accounts</h5>
                <p class="text-muted small mb-4 text-white">Connect your Github Account to import your repositories into
                    projects.</p>

                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3"
                    style="border: 1px solid var(--border);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-github fs-2 github-icon {{ Auth::user()->github_id ? 'connected' : '' }}"></i>
                        <div>
                            <h6 class="m-0 fw-bold text-white">GitHub</h6>
                            @if (Auth::user()->github_id)
                                <small class="text-white" style="opacity:.75;">Connected</small>
                            @else
                                <small class="text-white" style="opacity:.5;">Not connected</small>
                            @endif
                        </div>
                    </div>
                    @if (Auth::user()->github_id)
                        <a href="{{ route('social.disconnect', 'github') }}"
                            class="btn btn-outline-light d-flex align-items-center gap-2 btn-github-disconnect">
                            Disconnect
                        </a>
                    @else
                        <a href="{{ route('social.redirect', 'github') }}"
                            class="btn btn-outline-light d-flex align-items-center gap-2 btn-github-connect">
                            Connect
                        </a>

                    @endif
                </div>

            </div>
        </div>

        {{-- Crop Modal --}}
        <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-white" style="background-color: var(--card-bg);">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="cropModalLabel">Crop Profile Picture</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <div class="img-container" style="max-height: 400px; width: 100%; background-color: #000;">
                            <img id="imageToCrop" src="" style="max-width: 100%; display: block;">
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="cropAndSave">Crop & Save</button>
                    </div>
                </div>
            </div>
        </div>
@endsection

    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
        <style>
            .img-container img {
                display: block;
                max-width: 100%;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let cropper;
                const avatarInput = document.getElementById('avatarInput');
                const imageToCrop = document.getElementById('imageToCrop');
                const cropModalElement = document.getElementById('cropModal');
                const cropModal = new bootstrap.Modal(cropModalElement);
                const avatarPreview = document.getElementById('avatarPreview');
                const avatarBase64 = document.getElementById('avatarBase64');

                avatarInput.addEventListener('change', function (e) {
                    const files = e.target.files;
                    if (files && files.length > 0) {
                        const file = files[0];
                        const url = URL.createObjectURL(file);
                        imageToCrop.src = url;
                        cropModal.show();
                    }
                    // Clear input so same file can be selected again if cancelled
                    avatarInput.value = '';
                });

                cropModalElement.addEventListener('shown.bs.modal', function () {
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        background: false,
                    });
                });

                cropModalElement.addEventListener('hidden.bs.modal', function () {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    imageToCrop.src = '';
                });

                document.getElementById('cropAndSave').addEventListener('click', function () {
                    if (!cropper) return;

                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                    });

                    if (canvas) {
                        const base64Image = canvas.toDataURL('image/jpeg');

                        // Update preview
                        avatarPreview.src = base64Image;

                        // Update hidden input
                        avatarBase64.value = base64Image;
                    }

                    cropModal.hide();
                });
            });
        </script>
    @endpush