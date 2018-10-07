<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy extends Policy
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
     * @param Category $category
     * @return mixed
     */
    public function view(User $user, Category $category)
    {
        return false;
    }

    /**
     * Determine whether the user can create categorys.
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
     * @param Categsory $category
     * @return mixed
     */
    public function update(User $user, Category $category)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param  \App\Models\User $user
     * @param Category $category
     * @return mixed
     */
    public function delete(User $user, Category $category)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the category.
     *
     * @param  \App\Models\User $user
     * @param Category $category
     * @return mixed
     */
    public function restore(User $user, Category $category)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the category.
     *
     * @param  \App\Models\User $user
     * @param Category $category
     * @return mixed
     */
    public function forceDelete(User $user, Category $category)
    {
        return false;
    }
}
