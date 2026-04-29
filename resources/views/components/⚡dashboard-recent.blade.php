<?php

use Livewire\Component;
use App\Models\TaskAssignee;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

new class extends Component {
    public $checkTask;

    public function getTasks()
    {
        $recentTasks = Task::join('pools', 'tasks.pool_id', '=', 'pools.id')
            ->join('task_assignees', 'tasks.id', '=', 'task_assignees.task_id')
            ->join('user', 'task_assignees.user_email', '=', 'user.email')
            ->join('projects', 'pools.project_id', '=', 'projects.id')
            ->leftJoin('task_tags', 'tasks.id', '=', 'task_tags.task_id')
            ->leftJoin('tags', 'task_tags.tag_id', '=', 'tags.id')
            ->select(
                'tasks.id as task_id',
                'tasks.title as task_name',
                'tasks.description as task_desc',
                'tasks.start_date as stt_date',
                'tasks.end_date as end_date',
                'projects.name as project_name',
                'user.name as user_name',
                'user.email as user_email',
                'user.avatar as user_avtr',
                'tags.name as tag_name',
                'tags.color as tag_color'
            )
            ->orderByDesc('tasks.created_at')
            ->get()
            ->groupBy('task_id');


        return $recentTasks->take(10);
    }
};
?>
<div class="card rounded-4 p-0 overflow-hidden border-0 shadow-sm mb-4">
    <ul class="list-group list-group-flush" style="background-color: transparent;">
        @forelse ($this->getTasks() as $taskId => $taskRows)
            @php
                $task = $taskRows->first();
                $assignees = $taskRows->unique('user_email');
                $tags = $taskRows->filter(fn($row) => !is_null($row->tag_name))->unique('tag_name');
            @endphp
            <li class="list-group-item p-4" style="background-color: var(--bg-main); border-color: var(--border);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h6 class="m-0 fw-bold" style="color: var(--text-main);">{{ $task->task_name }}</h6>
                        </div>
                        <small class="text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-briefcase"></i> {{ $task->project_name }}
                            <span class="text-secondary">&bull;</span>
                            <i class="bi bi-calendar-event"></i>
                            @if(\Carbon\Carbon::parse($task->end_date)->isToday())
                                Due Today
                            @elseif(\Carbon\Carbon::parse($task->end_date)->isTomorrow())
                                Due Tomorrow
                            @else
                                Due {{ \Carbon\Carbon::parse($task->end_date)->format('M d') }}
                            @endif
                        </small>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="d-flex">
                            @foreach ($assignees as $index => $assignee)
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                                    style="width: 32px; height: 32px; border: 2px solid var(--bg-main); font-size: 0.8rem; z-index: {{ 10 - $index }}; {{ $index > 0 ? 'margin-left: -10px;' : '' }} background-color: #{{ substr(md5($assignee->user_name), 0, 6) }};">
                                    @if($assignee->user_avtr)
                                        @php
                                            $avatarUrl = Str::startsWith($assignee->user_avtr, ['http://', 'https://'])
                                                ? $assignee->user_avtr
                                                : \Illuminate\Support\Facades\Storage::url($assignee->user_avtr);
                                        @endphp
                                        <img src="{{ $avatarUrl }}" alt="{{ $assignee->user_name }}" class="rounded-circle"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ strtoupper(substr($assignee->user_name, 0, 2)) }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <p class="text-muted mb-3 mt-3" style="font-size: 0.95rem; line-height: 1.5;">
                    {{ $task->task_desc }}
                </p>

                <div class="d-flex gap-2">
                    @foreach ($tags as $tag)
                        <span class="badge bg-opacity-10 rounded-pill fw-normal px-2 py-1"
                            style="background-color: {{ $tag->tag_color }}20; color: {{ $tag->tag_color }}; border: 1px solid {{ $tag->tag_color }}40;">
                            {{ $tag->tag_name }}
                        </span>
                    @endforeach
                </div>
            </li>
        @empty
            <li class="list-group-item p-4 text-center text-muted border-0" style="background-color: var(--bg-main);">
                <p class="mb-0">No recent tasks available.</p>
            </li>
        @endforelse
    </ul>
</div>