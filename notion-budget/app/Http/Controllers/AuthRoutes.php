<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class AuthRoutes
{
    public function profile()
    {
        return view('profile');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function projects()
    {
        return view('projects');
    }

    public function projectsArchived()
    {
        return view('projects-archived');
    }

    public function projectBoard(Project $project)
    {
        return view('project-board', compact('project'));
    }

    public function projectMembers(Project $project)
    {
        return view('project-members', compact('project'));
    }
}
