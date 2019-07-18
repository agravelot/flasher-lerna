<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Cosplayer;

class CosplayerPolicy extends Policy
{
    /**
     * Determine whether the user can view the cosplayers.
     *
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the cosplayer.
     *
     * @param  User  $user
     * @param  Cosplayer|null  $cosplayer
     *
     * @return bool
     */
    public function view(User $user, ?Cosplayer $cosplayer = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create cosplayers.
     *
     * @param  User  $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the cosplayer.
     *
     * @param  User  $user
     * @param  Cosplayer|null  $cosplayer
     *
     * @return bool
     */
    public function update(User $user, ?Cosplayer $cosplayer = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the cosplayer.
     *
     * @param  User  $user
     * @param  Cosplayer|null  $cosplayer
     *
     * @return bool
     */
    public function delete(User $user, ?Cosplayer $cosplayer = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the cosplayer.
     *
     * @param  User  $user
     * @param  Cosplayer  $cosplayer
     *
     * @return bool
     */
    public function restore(User $user, Cosplayer $cosplayer): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the cosplayer.
     *
     * @param  User  $user
     * @param  Cosplayer  $cosplayer
     *
     * @return bool
     */
    public function forceDelete(User $user, Cosplayer $cosplayer): bool
    {
        return false;
    }
}
