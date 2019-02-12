<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Policies;

use App\Models\User;

class UserPolicy extends Policy
{
    /**
     * Determine whether the user can view the model.
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
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function view(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
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
     * Determine whether the user can update the model.
     *
     * @param User      $user
     * @param User|null $model
     *
     * @return bool
     */
    public function update(User $user, ?User $model = null)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User      $user
     * @param User|null $model
     *
     * @return bool
     */
    public function delete(User $user, ?User $model = null)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function restore(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     *
     * @return bool
     */
    public function forceDelete(User $user, User $model)
    {
        return false;
    }
}
