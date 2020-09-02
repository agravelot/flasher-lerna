<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\User;

class AlbumPolicy extends Policy
{
    /**
     * Determine whether the user can generate a signed download link for this album.
     */
    public function generateDownload(User $user, Album $album): bool
    {
        if (optional($user)->isAdmin()) {
            return true;
        }

        if ($album->isPublished() && $album->cosplayers->contains(Cosplayer::where('sso_id', auth()->id())->first())) {
            return true;
        }

        return false;
    }

    public function download(User $user, Album $album): bool
    {
        return $this->generateDownload($user, $album);
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
