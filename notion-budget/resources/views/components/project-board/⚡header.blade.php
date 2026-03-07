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
    public function project()
    {
        return Project::findOrFail($this->projectId);
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
            @can('roleBoardActions', $this->project)
                <button class="btn btn-outline-light btn-sm d-flex align-items-center gap-1"
                    style="border-radius:10px; font-size:.8rem;" data-bs-toggle="modal" data-bs-target="#addTagsModal">
                    <i class="bi bi-tag"></i> Add Tags
                </button>
            @endcan
            <a href="{{ route('project.members', $this->project->hashed_id) }}"
                class="btn btn-outline-light btn-sm d-flex align-items-center gap-1"
                style="border-radius:10px; font-size:.8rem;">
                <i class="bi bi-people"></i> Members
            </a>
            @can('roleBoardActions', $this->project)
                <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                    style="border-radius:10px; font-size:.8rem; box-shadow: 0 3px 12px rgba(139,92,246,.3);"
                    data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="bi bi-plus-lg"></i> Add Task
                </button>
            @endcan
        </div>
    </div>

    {{-- Add Tags Modal --}}
    @can('roleBoardActions', $this->project)
        <div class="modal fade" id="addTagsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-tag me-2"></i>Add Tag</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('project.tags.add', $this->projectHeader->hashed_id) }}" method="POST">
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
                            <input type="hidden" name="project_id" value="{{ $this->projectHeader->id }}">
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Tag</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    {{-- Members Modal --}}
    @can('roleUserManagement', $this->project)
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
    @endcan

    {{-- Add Task Modal --}}
    @can('roleBoardActions', $this->project)
        <div class="modal fade" id="addTaskModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Task</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="#" method="POST">
                        @csrf
                        <div class="modal-body">

                            {{-- Title (required) --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Task Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="What needs to be done?"
                                    required>
                            </div>

                            {{-- Description (optional) --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3"
                                    placeholder="Add details..."></textarea>
                            </div>

                            {{-- Priority & Tags row --}}
                            <div class="row mb-3">
                                {{-- Tags (optional) --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tags <span
                                            class="text-muted fw-normal">(optional)</span></label>
                                    <div class="tags-checkbox-list">
                                        @foreach($this->projectHeader->tags as $tag)
                                            <label class="tag-checkbox-item">
                                                <input type="checkbox" name="task_tags[]" value="{{ $tag->id }}">
                                                <span class="tag-color-dot" style="background: {{ $tag->color }};"></span>
                                                {{ $tag->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Start Date & End Date row --}}
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label small fw-semibold">Start Date <span
                                            class="text-muted fw-normal">(optional)</span></label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">End Date <span
                                            class="text-muted fw-normal">(optional)</span></label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>

                            {{-- Assign Users (optional) --}}
                            <div class="mb-1">
                                <label class="form-label small fw-semibold">Assign Users <span
                                        class="text-muted fw-normal">(optional)</span></label>
                                <div class="user-search-wrapper" x-data="{
                                                search: '',
                                                users: {{ json_encode($this->projectHeader->members->map(fn($m) => [
                                                    'name' => $m->user->name,
                                                    'email' => $m->user->email,
                                                    'avatar' => $m->user->avatar
                                                        ? (Str::startsWith($m->user->avatar, ['http://', 'https://'])
                                                            ? $m->user->avatar
                                                            : Storage::url($m->user->avatar))
                                                        : 'https://ui-avatars.com/api/?name=' . urlencode($m->user->name) . '&background=' . substr(md5($m->user->email), 0, 6) . '&color=fff&size=32&bold=true'
                                                ])->values()->toArray()) }},
                                                selected: [],
                                                get filtered() {
                                                    if (!this.search) return this.users;
                                                    return this.users.filter(u =>
                                                        u.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                                        u.email.toLowerCase().includes(this.search.toLowerCase())
                                                    );
                                                },
                                                toggle(user) {
                                                    const idx = this.selected.findIndex(s => s.email === user.email);
                                                    if (idx > -1) this.selected.splice(idx, 1);
                                                    else this.selected.push(user);
                                                },
                                                isSelected(email) {
                                                    return this.selected.some(s => s.email === email);
                                                }
                                            }">
                                    {{-- Search input --}}
                                    <div class="position-relative">
                                        <i class="bi bi-search user-search-icon"></i>
                                        <input type="text" class="form-control user-search-input"
                                            placeholder="Search by name..." x-model="search">
                                    </div>

                                    {{-- Selected users chips --}}
                                    <div class="selected-users-chips" x-show="selected.length > 0" x-cloak>
                                        <template x-for="user in selected" :key="user.email">
                                            <span class="user-chip">
                                                <img :src="user.avatar" class="user-chip-avatar" style="object-fit: cover;"
                                                    :alt="user.name">
                                                <span x-text="user.name"></span>
                                                <i class="bi bi-x-lg user-chip-remove" @click="toggle(user)"></i>
                                                <input type="hidden" name="assignees[]" :value="user.email">
                                            </span>
                                        </template>
                                    </div>

                                    {{-- User results list --}}
                                    <div class="user-results-list">
                                        <template x-for="user in filtered" :key="user.email">
                                            <div class="user-result-item" :class="{ 'selected': isSelected(user.email) }"
                                                @click="toggle(user)">
                                                <img :src="user.avatar" class="user-result-avatar"
                                                    style="object-fit: cover;" :alt="user.name">
                                                <div class="user-result-info">
                                                    <span class="user-result-name" x-text="user.name"></span>
                                                    <span class="user-result-email" x-text="user.email"></span>
                                                </div>
                                                <i class="bi bi-check-lg user-result-check"
                                                    x-show="isSelected(user.email)"></i>
                                            </div>
                                        </template>
                                        <div class="user-result-empty" x-show="filtered.length === 0">
                                            <i class="bi bi-person-x"></i> No users found
                                        </div>
                                    </div>
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
    @endcan
</div>