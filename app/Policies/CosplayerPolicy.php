<?php

namespace App\Policies;

use App\Models\Cosplayer;
use App\Models\User;

class CosplayerPolicy extends Policy
{
    /**
     * Determine whether the user can view the cosplayers.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the cosplayer.
     *
     * @param  \App\Models\User $user
     * @param Cosplayer $cosplayer
     * @return mixed
     */
    public function view(User $user, Cosplayer $cosplayer)
    {
        return false;
    }

    /**
     * Determine whether the user can create cosplayers.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the cosplayer.
     *
     * @param  \App\Models\User $user
     * @param Cosplayer $cosplayer
     * @return mixed
     */
    public function update(User $user, Cosplayer $cosplayer)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the cosplayer.
     *
     * @param  \App\Models\User $user
     * @param Cosplayer $cosplayer
     * @return mixed
     */
    public function delete(User $user, Cosplayer $cosplayer)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the cosplayer.
     *
     * @param  \App\Models\User $user
     * @param Cosplayer $cosplayer
     * @return mixed
     */
    public function restore(User $user, Cosplayer $cosplayer)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the cosplayer.
     *
     * @param  \App\Models\User $user
     * @param Cosplayer $cosplayer
     * @return mixed
     */
    public function forceDelete(User $user, Cosplayer $cosplayer)
    {
        return false;
    }
}
