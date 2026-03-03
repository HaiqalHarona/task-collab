<?php

use Livewire\Component;

new class extends Component {

    public $projectId;

    public function getMembers()
    {
        // Fake data for now - single sample
        return [
            [
                'name' => 'Johan Harona',
                'email' => 'johan@example.com',
                'avatar' => null,
                'role' => 'owner',
                'joined' => 'Feb 15, 2026',
            ],
        ];
    }
};
?>

<div>
    <link rel="stylesheet" href="{{ asset('css/members.css') }}">

    {{-- Back to board --}}
    <div class="members-breadcrumb mb-3">
        <a href="{{ route('project.board', request()->route('project')) }}">
            <i class="bi bi-arrow-left me-1"></i>Back to Board
        </a>
    </div>

    {{-- Page Header --}}
    <div class="members-page-header">
        <div>
            <h2><i class="bi bi-people-fill me-2"></i>Team Members</h2>
            <span class="text-muted" style="font-size:.85rem;">{{ count($this->getMembers()) }} members in this
                project</span>
        </div>
        <button class="btn btn-primary btn-sm d-flex align-items-center gap-1"
            style="border-radius:10px; font-size:.8rem; box-shadow: 0 3px 12px rgba(139,92,246,.3);"
            data-bs-toggle="modal" data-bs-target="#membersModal">
            <i class="bi bi-person-plus"></i> Invite Member
        </button>
    </div>

    {{-- Members Table --}}
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
                @foreach($this->getMembers() as $member)
                    <tr class="member-row">
                        <td>
                            <div class="member-info">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($member['name']) }}&background={{ $member['role'] === 'owner' ? '8b5cf6' : ($member['role'] === 'admin' ? '06b6d4' : '3f3f46') }}&color=fff&size=40&bold=true"
                                    class="member-avatar" alt="{{ $member['name'] }}">
                                <div>
                                    <div class="member-name">
                                        {{ $member['name'] }}
                                        @if($member['role'] === 'owner')
                                            <i class="bi bi-star-fill" style="color: #fbbf24; font-size:.7rem;"></i>
                                        @endif
                                    </div>
                                    <div class="member-email">{{ $member['email'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($member['role'] === 'owner')
                                <span class="role-badge role-owner">Owner</span>
                            @elseif($member['role'] === 'admin')
                                <span class="role-badge role-admin">Admin</span>
                            @elseif($member['role'] === 'member')
                                <span class="role-badge role-member">Member</span>
                            @else
                                <span class="role-badge role-viewer">Viewer</span>
                            @endif
                        </td>
                        <td>
                            <span class="member-joined">{{ $member['joined'] }}</span>
                        </td>
                        <td class="text-end">
                            <span class="text-muted" style="font-size:.75rem;">—</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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