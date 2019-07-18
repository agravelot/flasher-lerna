<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Policies;

use App\Models\User;
use App\Models\SocialMedia;

class SocialMediaPolicy extends Policy
{
    /**
     * Determine whether the user can view the socialMedias.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the socialMedia.
     *
     * @param User             $user
     * @param SocialMedia|null $socialMedia
     *
     * @return bool
     */
    public function view(User $user, ?SocialMedia $socialMedia = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create socialMedias.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the socialMedia.
     *
     * @param User             $user
     * @param SocialMedia|null $socialMedia
     *
     * @return bool
     */
    public function update(User $user, ?SocialMedia $socialMedia = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the socialMedia.
     *
     * @param User             $user
     * @param SocialMedia|null $socialMedia
     *
     * @return bool
     */
    public function delete(User $user, ?SocialMedia $socialMedia = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the socialMedia.
     *
     * @param User        $user
     * @param SocialMedia $socialMedia
     *
     * @return bool
     */
    public function restore(User $user, SocialMedia $socialMedia): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the socialMedia.
     *
     * @param User        $user
     * @param SocialMedia $socialMedia
     *
     * @return bool
     */
    public function forceDelete(User $user, SocialMedia $socialMedia): bool
    {
        return false;
    }
}
