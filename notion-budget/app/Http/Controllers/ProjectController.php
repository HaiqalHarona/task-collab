<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectMember;

class ProjectController
{
    public function ProjectCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon_base64' => 'nullable|string',
            'description' => 'nullable|string|max:255',
        ]);

        $iconPath = null;
        if ($request->filled('icon_base64')) {
            $base64 = $request->input('icon_base64');
            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64));
            $filename = 'project-icons/' . uniqid('proj_', true) . '.jpg';
            Storage::disk('public')->put($filename, $imageData);
            $iconPath = $filename;
        }

        $project = Project::create([
            'name' => $request->name,
            'icon' => $iconPath,
            'description' => $request->description,
            'owner_email' => Auth::user()->email,
            'status' => 'active',
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_email' => Auth::user()->email,
            'role' => 'owner',
        ]);

        return redirect()->route('projects')->with('success', 'Project created successfully!');
    }

    public function ProjectDelete()
    {

    }

    public function ProjectUpdate()
    {
    }

    public function ProjectEdit()
    {

    }
}
