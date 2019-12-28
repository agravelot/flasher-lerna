<?php

namespace App\Policies;

use App\Models\Album;
use App\Models\User;

class AlbumPolicy extends Policy
{
    /**
     * Determine whether the user can download the album.
     */
    public function download(User $user, Album $album): bool
    {
        if (optional($user)->isAdmin()) {
            return true;
        }

        if ($album->isPublic() && $album->cosplayers->contains($user->cosplayer)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the albums.
     *
     * @param  User  $user
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the album.
     *
     * @param  User  $user
     */
    public function view(?User $user, Album $album): bool
    {
        if ($album->isPublic()) {
            return true;
        }

        return $album->user->is($user);
    }

    /**
     * Determine whether the user can create albums.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the album.
     */
    public function update(User $user, Album $album): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the album.
     */
    public function delete(User $user, Album $album): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the album.
     */
    public function restore(User $user, Album $album): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the album.
     */
    public function forceDelete(User $user, Album $album): bool
    {
        return false;
    }
}
