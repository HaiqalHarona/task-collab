<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Task;
use App\Models\Pool;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Mail\ProjectInvatationMail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ProjectMemberController
{
    public function MemberInvite(Request $request)
    {
        // Validate basic fields
        $request->validate([
            'email' => ['required', 'email'],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'role' => ['required', Rule::in(['admin', 'member', 'viewer'])],
        ]);

        $email = $request->input('email');
        $role = $request->input('role');

        // Check if the email belongs to a registered user (uses session so Notyf picks it up)
        if (!User::where('email', $email)->exists()) {
            return back()->with('error', 'This email does not belong to a registered user.');
        }

        $project = Project::findOrFail($request->input('project_id'));

        // Manual authorization: check current user is a member of this project
        $isMember = ProjectMember::where('project_id', $project->id)
            ->where('user_email', Auth::user()->email)
            ->exists();

        if (!$isMember) {
            abort(403, 'You are not authorized to invite members to this project.');
        }

        // Check if the user is already a member of this project
        $alreadyMember = ProjectMember::where('project_id', $project->id)
            ->where('user_email', $email)
            ->exists();

        if ($alreadyMember) {
            return back()->with('error', 'This user is already a member of this project.');
        }

        // Fixed: route name was 'project.invite' but should be 'project.invite.accept'
        $signedUrl = URL::temporarySignedRoute('project.invite.accept', now()->addDays(7), [
            'project' => $project->hashed_id,
            'email' => $email,
            'role' => $role, // Pass role through signed URL
        ]);

        // Fixed: email was never actually being sent
        Mail::to($email)->send(new ProjectInvatationMail($signedUrl, $project->name));

        return back()->with('success', 'Invitation sent successfully!');
    }

    public function AcceptInvite(Request $request, Project $project)
    {
        $email = $request->query('email');
        $role = $request->query('role', 'member'); // Read role from signed URL

        // Validate role from URL
        if (!in_array($role, ['admin', 'member', 'viewer'])) {
            $role = 'member';
        }

        // Prevent Duplicate Invites
        $existingMember = ProjectMember::where('project_id', $project->id)
            ->where('user_email', $email)
            ->exists();
        if ($existingMember) {
            return redirect()->route('project.board', $project->hashed_id)->with('error', 'You are already a member of this project!');
        }

        ProjectMember::create([
            'project_id' => $project->id,
            'user_email' => $email,
            'role' => $role, // Use role from signed URL instead of hardcoded 'member'
        ]);

        return redirect()->route('project.board', $project->hashed_id)->with('success', 'You have joined the project successfully');
    }
}

