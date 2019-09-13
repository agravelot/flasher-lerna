<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Testimonial;

class TestimonialsPolicy extends Policy
{
    /**
     * Determine whether the user can view the categorys.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the category.
     */
    public function view(User $user, ?Testimonial $goldenBookPost = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create golden book post.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $user, Testimonial $goldenBookPost): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $user, ?Testimonial $goldenBookPost = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the category.
     */
    public function restore(User $user, Testimonial $goldenBookPost): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the category.
     */
    public function forceDelete(User $user, Testimonial $goldenBookPost): bool
    {
        return false;
    }
}
