<?php

use Livewire\Component;
use App\Models\TaskAssignee;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed; // Optimisation (Caching queries)


new class extends Component {
    public $projectTB;
    public $taskAssignee;

    #[Computed]
    public function active_project_count()
    {
        return Project::where(function ($query) {
            $query->where('owner_email', Auth::user()->email)
                ->orWhereHas('members', function ($q) {
                    $q->where('user_email', Auth::user()->email);
                });
        })
            ->where('status', 'active')
            ->count();
    }

    #[Computed]
    public function assigned_task_count()
    {
        return TaskAssignee::where('user_email', Auth::user()->email)->count();
    }

    // Get DB updates for project table
    public function checkProjectUpdates()
    {
        $checkProjectTB = Project::where(function ($query) {
            $query->where('owner_email', Auth::user()->email)
                ->orWhereHas('members', function ($q) {
                    $q->where('user_email', Auth::user()->email);
                });
        })->max('updated_at');

        if ($checkProjectTB === $this->projectTB) {
            // Stop livewire from re rendering the ui
            return;
        }

        $this->projectTB = $checkProjectTB;
    }

    // Get Assigned Task from Task_Assignees
    public function checkAssignTasks()
    {
        $checkTaskAssignee = Task::whereHas('task_assignees', function ($query) {
            $query->where('user_email', Auth::user()->email);
        })->max('updated_at');

        if ($checkTaskAssignee === $this->taskAssignee) {
            return;
        }

        $this->taskAssignee = $checkTaskAssignee;
    }


};
?>

<div class="row w-100 m-0 p-0">
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="card p-4 h-100 rounded-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="text-muted m-0">Assigned Tasks</h6>
                <i class="bi bi-clock-history fs-4 text-warning"></i>
            </div>
            <h2 class="fw-bold m-0" wire:poll.5s.visible="checkAssignTasks">
                {{ $this->assigned_task_count }}
            </h2>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 h-100 rounded-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="text-muted m-0">Active Projects</h6>
                <i class="bi bi-briefcase fs-4" style="color: var(--primary);"></i>
            </div>

            <h2 class="fw-bold m-0" wire:poll.5s.visible="checkProjectUpdates">
                {{ $this->active_project_count }}
            </h2>
        </div>
    </div>
</div>