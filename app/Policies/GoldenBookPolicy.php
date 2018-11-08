<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\GoldenBookPost;
use App\Models\User;

class GoldenBookPolicy extends Policy
{
    /**
     * Determine whether the user can view the categorys.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the category.
     *
     * @param  \App\Models\User $user
     * @param GoldenBookPost $goldenBookPost
     * @return mixed
     */
    public function view(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }

    /**
     * Determine whether the user can create golden book post.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param  \App\Models\User $user
     * @param GoldenBookPost $goldenBookPost
     * @return mixed
     */
    public function update(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param  \App\Models\User $user
     * @param GoldenBookPost $goldenBookPost
     * @return mixed
     */
    public function delete(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the category.
     *
     * @param  \App\Models\User $user
     * @param GoldenBookPost $goldenBookPost
     * @return mixed
     */
    public function restore(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the category.
     *
     * @param  \App\Models\User $user
     * @param GoldenBookPost $goldenBookPost
     * @return mixed
     */
    public function forceDelete(User $user, GoldenBookPost $goldenBookPost)
    {
        return false;
    }
}
