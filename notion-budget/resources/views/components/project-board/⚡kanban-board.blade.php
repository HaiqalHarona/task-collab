<?php

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
// use Livewire\Attributes\On;
use App\Models\Task;

new class extends Component {
    public $projectId;
    #[Computed]
    public function project()
    {
        return Project::findOrFail($this->projectId);
    }
    #[Computed]
    public function columns()
    {
        // Return from project Function
        return $this->project->pools;
    }
    #[Computed]
    public function tasks()
    {
        return Task::with(['comments', 'task_assignees.user', 'tags'])
            ->whereIn('pool_id', $this->project->pools->pluck('id'))
            ->get();
    }

    public function deleteTask($taskId)
    {
        $task = Task::find($taskId);

        if (!$task) {
            return response()->json(['message' => 'Task Not Found']);
        }

        $task->task_assignees()->delete();
        $task->tags()->delete();
        $task->delete();

        return response()->json(['message' => 'Task Deleted']);

    }
};
?>

<div class="kanban-board" x-data="{ activePoolId: null, editPoolName: '', editPoolColor: '#a78bfa' }">
    @foreach($this->columns as $pool)
        <div class="kanban-column">
            <div class="kanban-column-header">
                <div class="col-title">
                    <span
                        style="width:10px;height:10px;border-radius:50%;background:{{ $pool->color }};display:inline-block;"></span>
                    {{ $pool->name }}
                    <span class="col-count">{{ $this->tasks->where('pool_id', $pool->id)->count() }}</span>
                </div>
                <button class="btn btn-sm p-0" style="color:var(--text-muted);background:none;border:none;"
                    data-bs-toggle="modal" data-bs-target="#editPoolModal" data-pool-name="{{ $pool->name }}"
                    @click="activePoolId = {{ $pool->id }}; editPoolName = $el.dataset.poolName; editPoolColor = '{{ $pool->color }}'">
                    <i class="bi bi-three-dots"></i>
                </button>
            </div>
            <div class="kanban-column-body">
                @foreach($this->tasks->where('pool_id', $pool->id) as $task)
                    <div class="task-card position-relative" x-data="{ expanded: false }" @click="expanded = !expanded"
                        style="cursor: pointer;">
                        <button class="btn btn-sm btn-link text-danger position-absolute top-0 end-0 p-2" style="z-index: 10;"
                            title="Delete Task" @click.stop="if(confirm('Delete Task?')) { $wire.deleteTask({{ $task->id }}) }">
                            <i class="bi bi-trash"></i>
                        </button>
                        <div class="task-title pe-4">{{ $task->title }}</div>
                        <div class="task-desc">{{ $task->description }}</div>
                        <div class="task-dates">
                            @if($task->start_date)
                                <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($task->start_date)->format('M j') }}
                            @endif
                            @if($task->start_date && $task->end_date)
                                <span class="date-separator"><i class="bi bi-arrow-right"></i></span>
                            @endif
                            @if($task->end_date)
                                <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($task->end_date)->format('M j') }}
                            @endif
                        </div>
                        <div class="task-meta">
                            <div class="d-flex align-items-center gap-1">
                                @forelse($task->tags as $tag)
                                <span class="task-tag" style="background-color: {{ $tag->color }}15; color: {{ $tag->color }}">{{ $tag->name }}</span>
                                @empty
                                @endforelse
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="task-due"><i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($task->created_at)->format('M j') }}</span>
                                <div class="task-assignees-preview d-flex ms-1">
                                    @foreach($task->task_assignees->take(3) as $assignee)
                                        @if($assignee->user)
                                            @php
                                                $u = $assignee->user;
                                                $avatar = $u->avatar
                                                    ? (Str::startsWith($u->avatar, ['http://', 'https://']) ? $u->avatar : Storage::url($u->avatar))
                                                    : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=' . substr(md5($u->email), 0, 6) . '&color=fff&size=24&bold=true';
                                            @endphp
                                            <img src="{{ $avatar }}" class="task-avatar"
                                                style="margin-left: -5px; border: 2px solid var(--bg-color, #fff); border-radius: 50%;"
                                                alt="{{ $u->name }}" title="{{ $u->name }}">
                                        @endif
                                    @endforeach
                                    @if($task->task_assignees->count() > 3)
                                        <div class="task-avatar d-flex align-items-center justify-content-center"
                                            style="margin-left: -5px; border: 2px solid var(--bg-color, #fff); border-radius: 50%; background: var(--border-color, #e2e8f0); color: var(--text-color, #1e293b); font-size: 0.65rem; font-weight: bold; width: 24px; height: 24px; z-index: 1;">
                                            +{{ $task->task_assignees->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Expanded assigned users section --}}
                        <div x-show="expanded" x-transition x-cloak class="mt-3 pt-3 border-top" style="display: none;"
                            @click.stop>
                            <div class="small fw-bold text-muted mb-2">Assigned Users</div>
                            @forelse($task->task_assignees as $assignee)
                                @if($assignee->user)
                                    @php
                                        $u = $assignee->user;
                                        $avatar = $u->avatar
                                            ? (Str::startsWith($u->avatar, ['http://', 'https://']) ? $u->avatar : Storage::url($u->avatar))
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=' . substr(md5($u->email), 0, 6) . '&color=fff&size=32&bold=true';
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <img src="{{ $avatar }}" class="rounded-circle"
                                            style="width: 28px; height: 28px; object-fit: cover;" alt="{{ $u->name }}">
                                        <div class="d-flex flex-column">
                                            <span class="small fw-semibold"
                                                style="line-height:1.2; color: var(--text-color);">{{ $u->name }}</span>
                                            <span class="text-muted" style="font-size:0.75rem;line-height:1.2;">{{ $u->email }}</span>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="small text-muted fst-italic">No users assigned</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
                @can('roleBoardActions', $this->project)
                    <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                        style="border-radius:10px; font-size:.8rem; box-shadow: 0 3px 12px rgba(139,92,246,.3);"
                        data-bs-toggle="modal" data-bs-target="#addTaskModal" @click="activePoolId = {{ $pool->id }}">
                        <i class="bi bi-plus-lg"></i> Add Task
                    </button>
                @endcan
            </div>
        </div>
    @endforeach

    {{-- Add Pool --}}
    @can('roleBoardActions', $this->project)
        <div class="add-column" data-bs-toggle="modal" data-bs-target="#addColumnModal" style="cursor: pointer;">
            <i class="bi bi-plus-lg"></i> Add Pool
        </div>

        {{--
        =================================================Modals===========================================================
        --}}

        {{-- Add Pool Modal --}}
        <div class="modal fade" id="addColumnModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-layout-three-columns me-2"></i>Add Pool</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('project.pools.add', $this->project->hashed_id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pool Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. To Do, Review"
                                    required>
                            </div>
                            <div class="mb-1">
                                <label class="form-label small fw-semibold">Pool Color</label>
                                <input type="color" name="color" class="form-control form-control-color w-100"
                                    value="#a78bfa" title="Choose column color">
                            </div>
                            <input type="hidden" name="project_id" value="{{ $this->project->id }}">
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Pool</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('roleBoardActions', $this->project)
        {{-- Edit Pool Modal --}}
        <div class="modal fade" id="editPoolModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Pool</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="pool_id" :value="activePoolId">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pool Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. To Do, Review"
                                    required x-model="editPoolName">
                            </div>
                            <div class="mb-1">
                                <label class="form-label small fw-semibold">Pool Color</label>
                                <input type="color" name="color" class="form-control form-control-color w-100"
                                    title="Choose column color" x-model="editPoolColor">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('roleBoardActions', $this->project)
        <div class="modal fade" id="addTaskModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Task</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('project.task.add', $this->project->hashed_id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="pool_id" :value="activePoolId">

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
                                        @foreach($this->project->tags as $tag)
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
                                                                users: {{ json_encode($this->project->members->map(fn($m) => [
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