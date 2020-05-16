<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy extends Policy
{
    /**
     * Determine whether the user can view the invitations.
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
        if ($invitation->isAccepted() || $invitation->isExpired()) {
            return false;
        }

        return true;
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
