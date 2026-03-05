@extends('layouts.app')

@section('no-sidebar', true)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/project-board.css') }}">

    <livewire:project-board.header :projectId="$project->id" />
    {{-- Kanban Board --}}
    <div class="kanban-board">

        {{-- ── Column: To Do ── --}}
        <div class="kanban-column">
            <div class="kanban-column-header">
                <div class="col-title">
                    <span style="width:10px;height:10px;border-radius:50%;background:#a78bfa;display:inline-block;"></span>
                    To Do
                    <span class="col-count">3</span>
                </div>
                <button class="btn btn-sm p-0" style="color:var(--text-muted);background:none;border:none;">
                    <i class="bi bi-three-dots"></i>
                </button>
            </div>
            <div class="kanban-column-body">
                {{-- Task 1 --}}
                <div class="task-card">
                    <div class="task-title">Design landing page mockup</div>
                    <div class="task-desc">Create high-fidelity mockups for the new marketing landing page with responsive
                        layouts.</div>
                    <div class="task-meta">
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-tag tag-design">Design</span>
                            <span class="task-tag tag-high">High</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-due"><i class="bi bi-calendar3"></i> Mar 5</span>
                            <img src="https://ui-avatars.com/api/?name=JD&background=8b5cf6&color=fff&size=24&bold=true"
                                class="task-avatar" alt="JD">
                        </div>
                    </div>
                </div>

                {{-- Task 2 --}}
                <div class="task-card">
                    <div class="task-title">Set up project database schema</div>
                    <div class="task-desc">Define tables, relationships and migrations for the new project module.</div>
                    <div class="task-meta">
                        <span class="task-tag tag-feature">Feature</span>
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-due"><i class="bi bi-calendar3"></i> Mar 7</span>
                            <img src="https://ui-avatars.com/api/?name=AK&background=06b6d4&color=fff&size=24&bold=true"
                                class="task-avatar" alt="AK">
                        </div>
                    </div>
                </div>

                {{-- Task 3 --}}
                <div class="task-card">
                    <div class="task-title">Write API documentation</div>
                    <div class="task-meta">
                        <span class="task-tag tag-low">Low</span>
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-due"><i class="bi bi-calendar3"></i> Mar 10</span>
                            <img src="https://ui-avatars.com/api/?name=SM&background=10b981&color=fff&size=24&bold=true"
                                class="task-avatar" alt="SM">
                        </div>
                    </div>
                </div>

                <button class="add-task-btn"><i class="bi bi-plus"></i> Add task</button>
            </div>
        </div>

        {{-- ── Column: In Progress ── --}}
        <div class="kanban-column">
            <div class="kanban-column-header">
                <div class="col-title">
                    <span style="width:10px;height:10px;border-radius:50%;background:#fbbf24;display:inline-block;"></span>
                    In Progress
                    <span class="col-count">2</span>
                </div>
                <button class="btn btn-sm p-0" style="color:var(--text-muted);background:none;border:none;">
                    <i class="bi bi-three-dots"></i>
                </button>
            </div>
            <div class="kanban-column-body">
                {{-- Task 4 --}}
                <div class="task-card">
                    <div class="task-title">Implement user authentication flow</div>
                    <div class="task-desc">Build login, register, password reset with email verification using Laravel
                        Breeze.</div>
                    <div class="task-meta">
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-tag tag-feature">Feature</span>
                            <span class="task-tag tag-high">High</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-due"><i class="bi bi-calendar3"></i> Mar 4</span>
                            <img src="https://ui-avatars.com/api/?name=JD&background=8b5cf6&color=fff&size=24&bold=true"
                                class="task-avatar" alt="JD">
                        </div>
                    </div>
                </div>

                {{-- Task 5 --}}
                <div class="task-card">
                    <div class="task-title">Fix sidebar navigation bug on mobile</div>
                    <div class="task-desc">The offcanvas sidebar is not closing properly after link click on iOS Safari.
                    </div>
                    <div class="task-meta">
                        <span class="task-tag tag-bug">Bug</span>
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-due"><i class="bi bi-calendar3"></i> Mar 3</span>
                            <img src="https://ui-avatars.com/api/?name=RK&background=ef4444&color=fff&size=24&bold=true"
                                class="task-avatar" alt="RK">
                        </div>
                    </div>
                </div>

                <button class="add-task-btn"><i class="bi bi-plus"></i> Add task</button>
            </div>
        </div>

        {{-- ── Column: Review ── --}}
        <div class="kanban-column">
            <div class="kanban-column-header">
                <div class="col-title">
                    <span style="width:10px;height:10px;border-radius:50%;background:#22d3ee;display:inline-block;"></span>
                    Review
                    <span class="col-count">2</span>
                </div>
                <button class="btn btn-sm p-0" style="color:var(--text-muted);background:none;border:none;">
                    <i class="bi bi-three-dots"></i>
                </button>
            </div>
            <div class="kanban-column-body">
                {{-- Task 6 --}}
                <div class="task-card">
                    <div class="task-title">Review pull request #42</div>
                    <div class="task-desc">Code review for the new dashboard stats component and Livewire integration.</div>
                    <div class="task-meta">
                        <span class="task-tag tag-medium">Medium</span>
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-due"><i class="bi bi-calendar3"></i> Mar 3</span>
                            <img src="https://ui-avatars.com/api/?name=AK&background=06b6d4&color=fff&size=24&bold=true"
                                class="task-avatar" alt="AK">
                        </div>
                    </div>
                </div>

                {{-- Task 7 --}}
                <div class="task-card">
                    <div class="task-title">QA testing for profile page</div>
                    <div class="task-meta">
                        <span class="task-tag tag-medium">Medium</span>
                        <div class="d-flex align-items-center gap-1">
                            <span class="task-due"><i class="bi bi-calendar3"></i> Mar 4</span>
                            <img src="https://ui-avatars.com/api/?name=SM&background=10b981&color=fff&size=24&bold=true"
                                class="task-avatar" alt="SM">
                        </div>
                    </div>
                </div>

                <button class="add-task-btn"><i class="bi bi-plus"></i> Add task</button>
            </div>
        </div>

        {{-- ── Column: Done ── --}}
        <div class="kanban-column">
            <div class="kanban-column-header">
                <div class="col-title">
                    <span style="width:10px;height:10px;border-radius:50%;background:#34d399;display:inline-block;"></span>
                    Done
                    <span class="col-count">3</span>
                </div>
                <button class="btn btn-sm p-0" style="color:var(--text-muted);background:none;border:none;">
                    <i class="bi bi-three-dots"></i>
                </button>
            </div>
            <div class="kanban-column-body">
                {{-- Task 8 --}}
                <div class="task-card" style="opacity:.7;">
                    <div class="task-title" style="text-decoration:line-through;">Set up project repository</div>
                    <div class="task-meta">
                        <span class="task-tag tag-low">Low</span>
                        <img src="https://ui-avatars.com/api/?name=JD&background=8b5cf6&color=fff&size=24&bold=true"
                            class="task-avatar" alt="JD">
                    </div>
                </div>

                {{-- Task 9 --}}
                <div class="task-card" style="opacity:.7;">
                    <div class="task-title" style="text-decoration:line-through;">Configure CI/CD pipeline</div>
                    <div class="task-meta">
                        <span class="task-tag tag-feature">Feature</span>
                        <img src="https://ui-avatars.com/api/?name=RK&background=ef4444&color=fff&size=24&bold=true"
                            class="task-avatar" alt="RK">
                    </div>
                </div>

                {{-- Task 10 --}}
                <div class="task-card" style="opacity:.7;">
                    <div class="task-title" style="text-decoration:line-through;">Design system colour tokens</div>
                    <div class="task-meta">
                        <span class="task-tag tag-design">Design</span>
                        <img src="https://ui-avatars.com/api/?name=AK&background=06b6d4&color=fff&size=24&bold=true"
                            class="task-avatar" alt="AK">
                    </div>
                </div>

                <button class="add-task-btn"><i class="bi bi-plus"></i> Add task</button>
            </div>
        </div>

        {{-- ── Add Column ── --}}
        <div class="add-column">
            <i class="bi bi-plus-lg"></i> Add Column
        </div>

    </div>
@endsection