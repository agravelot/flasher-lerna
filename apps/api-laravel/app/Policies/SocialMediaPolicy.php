<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SocialMedia;
use App\Models\User;

class SocialMediaPolicy extends Policy
{
    /**
     * Determine whether the user can view the socialMedias.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the socialMedia.
     */
    public function view(User $user, ?SocialMedia $socialMedia = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create socialMedias.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the socialMedia.
     */
    public function update(User $user, ?SocialMedia $socialMedia = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the socialMedia.
     */
    public function delete(User $user, ?SocialMedia $socialMedia = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the socialMedia.
     */
    public function restore(User $user, SocialMedia $socialMedia): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the socialMedia.
     */
    public function forceDelete(User $user, SocialMedia $socialMedia): bool
    {
        return false;
    }
}
