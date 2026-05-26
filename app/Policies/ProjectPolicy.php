<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->isAdmin() || $user->isAuditor()) {
            return true;
        }

        return $user->department_id !== null && $user->department_id === $project->department_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isManager() && $user->department_id !== null && $user->department_id === $project->department_id;
    }

    /**
     * Determine whether the user can update project progress.
     */
    public function updateProgress(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return ($user->isManager() || $user->isOfficer()) 
            && $user->department_id !== null 
            && $user->department_id === $project->department_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isManager() && $user->department_id !== null && $user->department_id === $project->department_id;
    }
}
