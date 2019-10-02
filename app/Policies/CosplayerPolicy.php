<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cosplayer;

class CosplayerPolicy extends Policy
{
    /**
     * Determine whether the user can view the cosplayers.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the cosplayer.
     */
    public function view(User $user, ?Cosplayer $cosplayer = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create cosplayers.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the cosplayer.
     */
    public function update(User $user, ?Cosplayer $cosplayer = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the cosplayer.
     */
    public function delete(User $user, ?Cosplayer $cosplayer = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the cosplayer.
     */
    public function restore(User $user, Cosplayer $cosplayer): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the cosplayer.
     */
    public function forceDelete(User $user, Cosplayer $cosplayer): bool
    {
        return false;
    }
}
