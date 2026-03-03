<?php

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
/**
 * This component handles form submission for member invite and object creation within the project
 **/
new class extends Component {

    public $projectId;

    // Listens for stupid events like project updates from stupid owners and admins of the project
    #[On('project-updated')]

    public function refreshHeader()
    {
    }

    #[Computed]
    public function projectHeader()
    {
        /**
         Query to show the damn project header and the details on live update
        **/

        $header = Project::whereHas('members', function ($query) {
            $query->where('user_email', Auth::user()->email);
        })->where('status', 'active')->with('members.user')->where('id', $this->projectId)->withCount('members')->first();

        return $header;
    }
};
?>

<div>
    {{-- Board Header --}}
    <div class="board-header">
        <div class="board-icon">
            @if($this->projectHeader->icon)
                <img src="{{ Storage::url($this->projectHeader->icon) }}" alt="Project icon">
            @else
                <div class="board-icon"
                    style="background: {{ $this->projectHeader->color }}; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1.2rem;">
                    {{ strtoupper(substr($this->projectHeader->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div>
            <h2>{{ $this->projectHeader->name }}</h2>
            <span class="text-muted" style="font-size:.8rem;">4 pools &bull; 12 tasks &bull;
                {{ $this->projectHeader->members_count }} members</span>
        </div>
        <div class="ms-auto d-flex gap-2">
            <button class="btn btn-outline-light btn-sm d-flex align-items-center gap-1"
                style="border-radius:10px; font-size:.8rem;" data-bs-toggle="modal" data-bs-target="#addTagsModal">
                <i class="bi bi-tag"></i> Add Tags
            </button>
            <a href="{{ route('project.members', $this->projectHeader->hashed_id) }}"
                class="btn btn-outline-light btn-sm d-flex align-items-center gap-1"
                style="border-radius:10px; font-size:.8rem;">
                <i class="bi bi-people"></i> Members
            </a>
            <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                style="border-radius:10px; font-size:.8rem; box-shadow: 0 3px 12px rgba(139,92,246,.3);"
                data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="bi bi-plus-lg"></i> Add Task
            </button>
        </div>
    </div>

    {{-- Add Tags Modal --}}
    <div class="modal fade" id="addTagsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-tag me-2"></i>Add Tag</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Tag Name</label>
                            <input type="text" name="tag_name" class="form-control" placeholder="e.g. Bug, Feature"
                                required>
                        </div>
                        <div class="mb-1">
                            <label class="form-label small fw-semibold">Tag Color</label>
                            <input type="color" name="tag_color" class="form-control form-control-color w-100"
                                value="#6c63ff" title="Choose tag color">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Members Modal --}}
    <div class="modal fade" id="membersModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-people me-2"></i>Invite Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('project.invite.email') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">User Email</label>
                            <input type="email" name="email" class="form-control" placeholder="user@example.com"
                                required>
                            <input type="hidden" name="project_id" value="{{ $this->projectHeader->id }}">
                        </div>
                        <div class="mb-1">
                            <label class="form-label small fw-semibold">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="member" selected>Member</option>
                                <option value="admin">Admin</option>
                                <option value="viewer">Viewer</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Invite</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Task Modal --}}
    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Task Title</label>
                            <input type="text" name="title" class="form-control" placeholder="What needs to be done?"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Add details..."></textarea>
                        </div>
                        <div class="mb-1">
                            <label class="form-label small fw-semibold">Assignee (Optional)</label>
                            <input type="email" name="assignee_email" class="form-control"
                                placeholder="Assign to email...">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>