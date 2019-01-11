<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Policies;

use App\Models\Cosplayer;
use App\Models\User;

class CosplayerPolicy extends Policy
{
    /**
     * Determine whether the user can view the cosplayers.
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the cosplayer.
     */
    public function view(User $user, ?Cosplayer $cosplayer = null)
    {
        return false;
    }

    /**
     * Determine whether the user can create cosplayers.
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the cosplayer.
     */
    public function update(User $user, ?Cosplayer $cosplayer = null)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the cosplayer.
     */
    public function delete(User $user, ?Cosplayer $cosplayer = null)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the cosplayer.
     */
    public function restore(User $user, Cosplayer $cosplayer)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the cosplayer.
     */
    public function forceDelete(User $user, Cosplayer $cosplayer)
    {
        return false;
    }
}
