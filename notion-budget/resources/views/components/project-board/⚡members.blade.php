<?php

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

new class extends Component {

    public $projectId;

    #[Computed]
    public function project()
    {
        return Project::findOrFail($this->projectId);
    }

    #[Computed]
    public function getMembers()
    {
        $members = ProjectMember::where('project_id', $this->projectId)
            ->with('user')
            ->get();

        // Sort: owner first, then logged-in user, then everyone else
        $currentEmail = Auth::user()->email;

        return $members->sortBy(function ($member) use ($currentEmail) {
            if ($member->role === 'owner')
                return 0;
            if ($member->user_email === $currentEmail)
                return 1;
            return 2;
        })->values();
    }

    public function removeMember($memberId)
    {
        $member = ProjectMember::where('id', $memberId)
            ->where('project_id', $this->projectId)
            ->first();

        if ($member && $member->role !== 'owner') {
            $member->delete();
            unset($this->getMembers);
            session()->flash('success', 'Member removed successfully.');
        }
    }
};
?>

<div>
    <link rel="stylesheet" href="{{ asset('css/members.css') }}">

    {{-- Back to board --}}
    <div class="members-breadcrumb mb-3">
        <a href="{{ route('project.board', $this->project->hashed_id) }}">
            <i class="bi bi-arrow-left me-1"></i>Back to Board
        </a>
    </div>

    {{-- Page Header --}}
    <div class="members-page-header">
        <div>
            <h2><i class="bi bi-people-fill me-2"></i>Team Members</h2>
            <span class="text-muted" style="font-size:.85rem;">{{ $this->getMembers->count() }} members in this
                project</span>
        </div>
        @can('roleUserManagement', $this->project)
            <div class="d-flex gap-2">
                <button type="submit" form="roleChangeForm"
                    class="btn btn-outline-success btn-sm d-flex align-items-center gap-1"
                    style="border-radius:10px; font-size:.8rem;">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
                <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                    style="border-radius:10px; font-size:.8rem; box-shadow: 0 3px 12px rgba(139,92,246,.3);"
                    data-bs-toggle="modal" data-bs-target="#membersModal">
                    <i class="bi bi-person-plus"></i> Invite Member
                </button>
            </div>
        @endcan
    </div>

    {{-- Members Table --}}
    <form id="roleChangeForm" action="" method="POST">
        @csrf
        <input type="hidden" name="project_id" value="{{ $this->projectId }}">
        <div class="members-table-wrapper">
            <table class="members-table">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getMembers as $member)
                        <input type="hidden" name="user_email" value="{{ $member->user_email }}">

                        <tr class="member-row">
                            <td>
                                <div class="member-info">
                                    @if($member->user->avatar)
                                        <img src="{{ Str::startsWith($member->user->avatar, ['http://', 'https://']) ? $member->user->avatar : Storage::url($member->user->avatar) }}"
                                            class="member-avatar" alt="{{ $member->user->name }}">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->user->name) }}&background={{ $member->role === 'owner' ? '8b5cf6' : ($member->role === 'admin' ? '06b6d4' : '3f3f46') }}&color=fff&size=40&bold=true"
                                            class="member-avatar" alt="{{ $member->user->name }}">
                                    @endif
                                    <div>
                                        <div class="member-name">
                                            {{ $member->user->name }}
                                            @if($member->role === 'owner')
                                                <i class="bi bi-star-fill" style="color: #fbbf24; font-size:.7rem;"></i>
                                            @endif
                                            @if($member->user_email === Auth::user()->email)
                                                <span class="badge bg-secondary"
                                                    style="font-size:.6rem; vertical-align:middle;">You</span>
                                            @endif
                                        </div>
                                        <div class="member-email">{{ $member->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($member->role === 'owner')
                                    <span class="role-badge role-owner">Owner</span>
                                @else
                                    @can('roleUserManagement', $this->project)
                                        <select class="form-select form-select-sm"
                                            style="width:auto; font-size:.8rem; border-radius:8px;" name="roles[{{ $member->id }}]">
                                            <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="member" {{ $member->role === 'member' ? 'selected' : '' }}>Member</option>
                                            <option value="viewer" {{ $member->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                        </select>
                                    @endcan
                                @endif
                            </td>
                            <td>
                                <span class="member-joined">{{ $member->added_at?->diffForHumans() ?? '—' }}</span>
                            </td>
                            <td class="text-end">
                                @if($member->user_email !== Auth::user()->email && $member->role !== 'owner')
                                    @can('roleUserManagement', $this->project)
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="if(confirm('Are you sure you want to remove {{ $member->user->name }} from this project?')) { @this.removeMember({{ $member->id }}) }">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                @else
                                    <span class="text-muted" style="font-size:.75rem;">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

    {{-- Members Invite Modal --}}
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
                            <input type="hidden" name="project_id" value="{{ $this->projectId }}">
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
</div>