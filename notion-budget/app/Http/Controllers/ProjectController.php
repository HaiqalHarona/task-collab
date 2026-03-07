<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectMember;
use App\Models\Tag;
use App\Models\TaskTag;

class ProjectController
{
    public function ProjectCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon_base64' => 'nullable|string',
            'description' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
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
            'color' => $request->color ?? '#6c63ff',
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

    public function ProjectDelete(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::find($request->project_id);
        if ($project->owner_email != Auth::user()->email) {
            return redirect()->route('projects')->with('error', 'You are not authorized to delete this project!');
        } elseif ($project->status == 'archived') {
            return redirect()->route('projects')->with('error', 'Project is already archived!');
        } elseif (!$project) {
            return redirect()->route('projects')->with('error', 'Project not found!');
        }
        $project->update([
            'status' => 'archived',
        ]);

        return redirect()->route('projects')->with('success', 'Project archived successfully!');
    }

    public function ProjectUpdate(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'icon_base64' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'description' => 'nullable|string|max:255',
        ]);

        $projectUpdateData = [];

        if ($request->filled('name')) {
            $projectUpdateData['name'] = $request->name;
        }

        if ($request->filled('icon_base64')) {
            $base64 = $request->input('icon_base64');
            $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64));
            $filename = 'project-icons/' . uniqid('proj_', true) . '.jpg';
            Storage::disk('public')->put($filename, $imageData);
            $projectUpdateData['icon'] = $filename;
        }

        if ($request->filled('color')) {
            $projectUpdateData['color'] = $request->color;
        }

        if ($request->filled('description')) {
            $projectUpdateData['description'] = $request->description;
        }

        if (!empty($projectUpdateData)) {
            Project::where('id', $request->project_id)->update($projectUpdateData);
            return redirect()->route('projects')->with('success', 'Project updated successfully!');
        }

        return redirect()->route('projects')->with('error', 'No changes to update.');
    }

    public function ProjectRestore(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::find($request->project_id);
        if ($project->owner_email != Auth::user()->email) {
            return redirect()->route('projects')->with('error', 'You are not authorized to restore this project!');
        } elseif ($project->status == 'active') {
            return redirect()->route('projects')->with('error', 'Project is already active!');
        } elseif (!$project) {
            return redirect()->route('projects')->with('error', 'Project not found!');
        }

        $project->update([
            'status' => 'active',
        ]);

        return redirect()->route('projects')->with('success', 'Project restored successfully!');
    }

    public function UpdateRoles(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'roles' => 'required|array',
            'roles.*' => 'in:admin,member,viewer',
            'user_email' => 'required|email|exists:project_members,user_email',
        ]);
        // Finish This HOBO
        $projectFind = Project::find($request->project_id);

    }

    public function AddTags(Request $request, Project $project)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tag_name' => 'required|string|max:255',
            'tag_color' => 'required|string|max:7',
        ]);

        $tag = Tag::create([
            'project_id' => $project->id,
            'name' => $request->tag_name,
            'color' => $request->tag_color,
        ]);

        if ($tag) {
            return redirect()->route('project.board', $project->hashed_id)->with('success', 'Tag Created Successfully');
        } else {
            return redirect()->route('project.board', $project->hashed_id)->with('error', 'Tag Not Created');
        }

    }

    public function AddTask(Request $request, Project $project)
    {
        
    }
}
