<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Policies;

use App\Models\GoldenBookPost;
use App\Models\User;

class GoldenBookPolicy extends Policy
{
    /**
     * Determine whether the user can view the categorys.
     *
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the category.
     *
     * @param User                $user
     * @param GoldenBookPost|null $goldenBookPost
     *
     * @return bool
     */
    public function view(User $user, ?GoldenBookPost $goldenBookPost = null)
    {
        return false;
    }

    /**
     * Determine whether the user can create golden book post.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param User           $user
     * @param GoldenBookPost $goldenBookPost
     *
     * @return bool
     */
    public function update(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param User                $user
     * @param GoldenBookPost|null $goldenBookPost
     *
     * @return bool
     */
    public function delete(User $user, ?GoldenBookPost $goldenBookPost = null)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the category.
     *
     * @param User           $user
     * @param GoldenBookPost $goldenBookPost
     *
     * @return bool
     */
    public function restore(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the category.
     *
     * @param User           $user
     * @param GoldenBookPost $goldenBookPost
     *
     * @return bool
     */
    public function forceDelete(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }
}
