<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectMember;

class ProjectRoleRestrictions
{
    /**
     * Create a new policy instance.
     */
    public function roleView(User $user, Project $project)
    {
        if($user->email === $project->owner_email) {
            return true;
        }
        return $project->members()->where('user_email', $user->email)->exists();
    }
}
