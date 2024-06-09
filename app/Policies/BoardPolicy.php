<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BoardPolicy
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
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        //
        return $user->id == $project->owner_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        //
        return $user->id == $project->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        //
        return $user->id == $project->owner_id;
    }
}
