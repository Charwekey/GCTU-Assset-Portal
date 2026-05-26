<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view their scoped assets
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Asset $asset): bool
    {
        if ($user->isAdmin() || $user->isAuditor()) {
            return true;
        }

        // Managers and Officers can only view assets in their own department
        return $user->department_id !== null && $user->department_id === $asset->department_id;
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
    public function update(User $user, Asset $asset): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isManager() && $user->department_id !== null && $user->department_id === $asset->department_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Asset $asset): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isManager() && $user->department_id !== null && $user->department_id === $asset->department_id;
    }

    /**
     * Determine whether the user can log maintenance for the asset.
     */
    public function logMaintenance(User $user, Asset $asset): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return ($user->isManager() || $user->isOfficer()) 
            && $user->department_id !== null 
            && $user->department_id === $asset->department_id;
    }
}
