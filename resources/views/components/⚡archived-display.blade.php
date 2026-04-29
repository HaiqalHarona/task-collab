<?php

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function projects()
    {
        // Query archived projects
        $projects = Project::whereHas('members', function ($query) {
            $query->where('user_email', Auth::user()->email);
        })->where('status', 'archived')->with('members.user')->withCount('members')->get();

        return $projects;
    }

};
?>

<div>
    {{-- Projects Grid --}}
    <div class="row g-4" wire:poll.5s.visible>
        @forelse($this->projects as $project)
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card h-100 rounded-4 border-0 shadow-sm project-card"
                    style="cursor:pointer; transition: transform .18s, box-shadow .18s;">

                    <div class="rounded-top-4 d-flex align-items-center gap-3 px-4 py-3 position-relative"
                        style="background: {{ $project->color }}; min-height: 80px;"> {{-- Project Colour --}}

                        <div class="flex-shrink-0 rounded-3 overflow-hidden"
                            style="width:52px; height:52px; box-shadow: 0 2px 8px rgba(0,0,0,.18);">
                            @if($project->icon)
                                <img src="{{ Storage::url($project->icon) }}" width="52" height="52" {{-- Project Icon --}}
                                    alt="Project icon" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <div class="board-icon"
                                    style="background-color: grey; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1.2rem; width: 52px; height: 52px;">
                                    {{ strtoupper(substr($project->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="overflow-hidden flex-grow-1">
                            <h5 class="fw-bold text-white m-0 text-truncate">{{ $project->name }}</h5>{{-- Project Name --}}
                            <span class="badge rounded-pill text-white mt-1"
                                style="background:rgba(255,255,255,.2); font-size:.7rem;">
                                Archived
                            </span>
                        </div>
                    </div>

                    <div class="card-body px-4 pt-3 pb-2">
                        <p class="text-muted small mb-3" style="line-height:1.55;">
                            {{ $project->description }} {{-- Project Description --}}
                        </p>

                        <div class="d-flex gap-3 mb-3">
                            <div class="d-flex align-items-center gap-1 text-muted small">
                                <i class="bi bi-check2-square"></i>
                                <span>Task Count Placeholder</span> {{-- Task Count --}}
                            </div>
                        </div>
                    </div>

                    {{-- Project Members --}}
                    <div
                        class="card-footer bg-transparent border-top-0 px-4 pb-3 pt-0 d-flex align-items-center justify-content-between">

                        <div class="d-flex align-items-center">
                            <div class="rounded-circle border border-2 border-white overflow-hidden"
                                style="width:32px;height:32px;margin-right:-10px;z-index:4;box-shadow:0 1px 4px rgba(0,0,0,.15);"
                                title="Alice">
                                <img src="https://ui-avatars.com/api/?name=Alice&background=6c63ff&color=fff&size=32"
                                    width="32" height="32" alt="Alice">
                            </div>
                            <div class="rounded-circle border border-2 border-white d-flex align-items-center justify-content-center bg-secondary text-white"
                                style="width:32px;height:32px;z-index:1;font-size:.65rem;font-weight:700;box-shadow:0 1px 4px rgba(0,0,0,.15);">
                                +2
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('project.restore') }}" method="POST" style="margin: 0;">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                <button type="submit" class="btn btn-sm rounded-pill px-3 btn-success">
                                    <i class="bi bi-arrow-counterclockwise ms-1"></i> Restore
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-archive fs-1"></i>
                <p class="mt-3">You don't have any archived projects.</p>
            </div>
        @endforelse

    </div>

    <style>
        .project-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(108, 99, 255, .18) !important;
        }
    </style>
</div>