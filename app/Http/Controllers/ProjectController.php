<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ProjectMember;
use App\Models\Tag;
use App\Models\TaskTag;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\Pool;

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

    public function UpdateRoles(Request $request, Project $project)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'roles' => 'required|array',
            'roles.*' => 'in:admin,member,viewer',
        ]);

        // Authorization check: Only owner and admin can manage users
        if (!Auth::user()->can('roleUserManagement', $project)) {
            return redirect()->back()->with('error', 'You are not authorized to manage roles.');
        }

        foreach ($request->roles as $memberId => $newRole) {
            $member = $project->members()->where('id', $memberId)->first();

            // Do not allow changing the owner's role
            if ($member && $member->role !== 'owner') {
                $member->update(['role' => $newRole]);
            }
        }

        return redirect()->back()->with('success', 'Roles updated successfully!');
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

    public function AddTasks(Request $request, Project $project)
    {
        $request->validate([
            'pool_id' => 'required|exists:pools,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'task_tags' => 'nullable|array',
            'task_tags.*' => 'exists:tags,id',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:user,email',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $task = Task::create([
            'pool_id' => $request->pool_id,
            'description' => $request->description,
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if (!$task) {
            return redirect()->route('project.board', $project->hashed_id)->with('error', 'Task Not Created');
        }

        if ($request->has('task_tags')) {

            foreach ($request->task_tags as $tag) {
                TaskTag::create([
                    'task_id' => $task->id,
                    'tag_id' => $tag,
                ]);
            }
        }

        if ($request->has('assignees')) {
            foreach ($request->assignees as $assignees) {
                TaskAssignee::create([
                    'task_id' => $task->id,
                    'user_email' => $assignees,
                ]);
            }
        }

        broadcast(new \App\Events\BoardUpdates(
            $project->id,
        ));

        return redirect()->route('project.board', $project->hashed_id)->with('success', 'Task Created Successfully');

    }

    public function EditTasks(Request $request, Project $project)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'task_tags' => 'nullable|array',
            'task_tags.*' => 'exists:tags,id',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:user,email',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $task = Task::findOrFail($request->task_id);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        TaskTag::where('task_id', $task->id)->delete();
        if ($request->has('task_tags')) {
            foreach ($request->task_tags as $tag) {
                TaskTag::create([
                    'task_id' => $task->id,
                    'tag_id' => $tag,
                ]);
            }
        }

        TaskAssignee::where('task_id', $task->id)->delete();
        if ($request->has('assignees')) {
            foreach ($request->assignees as $assignee) {
                TaskAssignee::create([
                    'task_id' => $task->id,
                    'user_email' => $assignee,
                ]);
            }
        }

        return redirect()->route('project.board', $project->hashed_id)->with('success', 'Task Updated Successfully');
    }

    public function AddPools(Request $request, Project $project)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7'
        ]);

        $pool = Pool::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'color' => $request->color ?? '#6c63ff'
        ]);
        if ($pool) {
            return redirect()->route('project.board', $project->hashed_id)->with('success', 'Pool Created Successfully');
        } else {
            return redirect()->route('project.board', $project->hashed_id)->with('error', 'Pool Not Created');

        }
    }

    public function EditPools(Request $request, Project $project)
    {
        $request->validate([
            'pool_id' => 'required|exists:pools,id',
            'name' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7'
        ]);

        // If your form always submits both fields, you can pass them directly:
        $pool = Pool::where('id', $request->pool_id)->update(
            $request->only(['name', 'color'])
        );

        if (!$pool) {
            return redirect()->route('project.board', $project->hashed_id)->with('error', 'Pool Not Updated');
        }

        return redirect()->route('project.board', $project->hashed_id)->with('success', 'Pool Updated Successfully');
    }
}

