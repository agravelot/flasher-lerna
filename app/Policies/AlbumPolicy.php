<?php

namespace App\Policies;

use App\Models\Album;
use App\Models\User;

class AlbumPolicy extends Policy
{
    /**
     * Determine whether the user can view the albums.
     *
     * @param  User $user
     * @return mixed
     */
    public function index(?User $user)
    {
//        return $user->id == $album->user_id;
        return false;
    }

    /**
     * Determine whether the user can view the album.
     *
     * @param  User $user
     * @param Album $album
     * @return mixed
     */
    public function view(?User $user, Album $album)
    {
        if ($album->isPublic()) {
            return true;
        } elseif ($user === null) {
            return false;
        }

//        return $user == null && $user->id === $album->user_id;
        return $user->id === $album->user_id;
    }

    /**
     * Determine whether the user can create albums.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user)
    {
//        return $user->id == $album->user_id;
        return false;
    }

    /**
     * Determine whether the user can update the album.
     *
     * @param  User $user
     * @param Album $album
     * @return mixed
     */
    public function update(Album $album, User $user)
    {
        return $user->id == $album->user_id;
    }

    /**
     * Determine whether the user can delete the album.
     *
     * @param  User $user
     * @param Album $album
     * @return mixed
     */
    public function delete(User $user, Album $album)
    {
        return $user->id == $album->user_id;
    }

    /**
     * Determine whether the user can restore the album.
     *
     * @param  User $user
     * @param Album $album
     * @return mixed
     */
    public function restore(User $user, Album $album)
    {
        return $user->id == $album->user_id;
    }

    /**
     * Determine whether the user can permanently delete the album.
     *
     * @param  User $user
     * @param Album $album
     * @return mixed
     */
    public function forceDelete(User $user, Album $album)
    {
        return $user->id == $album->user_id;
    }
}
