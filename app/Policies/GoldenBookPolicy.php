<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Policies;

use App\Models\Category;
use App\Models\GoldenBookPost;
use App\Models\User;

class GoldenBookPolicy extends Policy
{
    /**
     * Determine whether the user can view the categorys.
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the category.
     */
    public function view(User $user, ?GoldenBookPost $goldenBookPost = null)
    {
        return false;
    }

    /**
     * Determine whether the user can create golden book post.
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $user, ?GoldenBookPost $goldenBookPost = null)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the category.
     */
    public function restore(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the category.
     */
    public function forceDelete(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }
}
