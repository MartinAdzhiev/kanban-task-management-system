<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project, $members): bool
    {
        //
        foreach ($members as $member) {
            if ($member->user_id == $user->id) {
                return true;
            }
        }
        return $user->id == $project->owner_id;
    }

    /**
     * Determine whether the user can update, delete or add members to project.
     */
    public function update(User $user, Project $project): bool
    {
        //
        return $user->id == $project->owner_id;
    }

    public function delete(User $user, Project $project): bool
    {
        //
        return $user->id == $project->owner_id;
    }

    public function addMember(User $user, Project $project): bool
    {
        //
        return $user->id == $project->owner_id;
    }
}
