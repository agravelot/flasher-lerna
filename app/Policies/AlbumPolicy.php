<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Album;

class AlbumPolicy extends Policy
{
    /**
     * Determine whether the user can download the album.
     *
     * @param  User  $user
     * @param  Album  $album
     *
     * @return bool
     */
    public function download(User $user, Album $album): bool
    {
        if ($user && $user->isAdmin()) {
            return true;
        }

        if (! $album->isPublic()) {
            return false;
        }

        return $album->cosplayers->contains($user->cosplayer);
    }

    /**
     * Determine whether the user can view the albums.
     *
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
//        return $user->id == $album->user_id;
        return true;
    }

    /**
     * Determine whether the user can view the album.
     *
     * @param  User  $user
     * @param  Album  $album
     *
     * @return bool
     */
    public function view(?User $user, Album $album): bool
    {
        if ($album->isPublic()) {
            return true;
        } elseif ($user == null) {
            return false;
        }

//        return $user == null && $user->id === $album->user_id;
        return $user->id === $album->user_id;
    }

    /**
     * Determine whether the user can create albums.
     *
     * @param  User  $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
//        return $user->id == $album->user_id;
        return false;
    }

    /**
     * Determine whether the user can update the album.
     *
     * @param  User  $user
     * @param  Album  $album
     *
     * @return bool
     */
    public function update(User $user, Album $album): bool
    {
//        return $user->id == $album->user_id;
        return false;
    }

    /**
     * Determine whether the user can delete the album.
     *
     * @param  User  $user
     * @param  Album  $album
     *
     * @return bool
     */
    public function delete(User $user, Album $album): bool
    {
//        return $user->id == $album->user_id;
        return false;
    }

    /**
     * Determine whether the user can restore the album.
     *
     * @param  User  $user
     * @param  Album  $album
     *
     * @return bool
     */
    public function restore(User $user, Album $album): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the album.
     *
     * @param  User  $user
     * @param  Album  $album
     *
     * @return bool
     */
    public function forceDelete(User $user, Album $album): bool
    {
        return false;
    }
}
