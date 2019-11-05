<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invitation;

class InvitationPolicy extends Policy
{
    /**
     * Determine whether the user can view the invitations.
     *
     * @param  User  $user
     */
    public function viewAny(?User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the invitation.
     */
    public function view(?User $user, Invitation $invitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create invitations.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the invitation.
     */
    public function update(User $user, Invitation $invitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the invitation.
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the invitation.
     */
    public function restore(User $user, Invitation $invitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the invitation.
     */
    public function forceDelete(User $user, Invitation $invitation): bool
    {
        return false;
    }
}
