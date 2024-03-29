<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy extends Policy
{
    /**
     * Determine whether the user can view the categories.
     *
     * @param  User  $user
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the category.
     *
     * @param  Category|null  $category
     */
    public function view(User $user, Category $category): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create categorys.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param  Category|null  $category
     */
    public function update(User $user, Category $category): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param  Category|null  $category
     */
    public function delete(User $user, Category $category): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the category.
     */
    public function restore(User $user, Category $category): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the category.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }
}
