<?php

namespace App\Policies;

use App\Models\Procurement;
use App\Models\User;

class ProcurementPolicy
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
    public function view(User $user, Procurement $procurement): bool
    {
        if ($user->isAdmin() || $user->isAuditor()) {
            return true;
        }

        return $user->department_id !== null && $user->department_id === $procurement->department_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins, Managers, and Officers can initiate procurements. Auditors cannot.
        return !$user->isAuditor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Procurement $procurement): bool
    {
        if ($procurement->status !== 'pending') {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        // Officer can update if they initiated it
        if ($user->isOfficer() && $procurement->initiated_by === $user->id) {
            return true;
        }

        // Manager can update if it is in their department
        return $user->isManager() && $user->department_id !== null && $user->department_id === $procurement->department_id;
    }

    /**
     * Determine whether the user can approve the procurement.
     */
    public function approve(User $user, Procurement $procurement): bool
    {
        if ($procurement->status !== 'pending') {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        // Managers can approve if it belongs to their department
        return $user->isManager() && $user->department_id !== null && $user->department_id === $procurement->department_id;
    }

    /**
     * Determine whether the user can cancel the procurement.
     */
    public function cancel(User $user, Procurement $procurement): bool
    {
        if (in_array($procurement->status, ['completed', 'cancelled'])) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        // Officer can cancel if they initiated it and it's still pending
        if ($user->isOfficer() && $procurement->status === 'pending' && $procurement->initiated_by === $user->id) {
            return true;
        }

        // Manager can cancel/reject if it is in their department
        return $user->isManager() && $user->department_id !== null && $user->department_id === $procurement->department_id;
    }
}
