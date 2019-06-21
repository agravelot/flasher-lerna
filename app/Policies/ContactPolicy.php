<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Contact;

class ContactPolicy extends Policy
{
    /**
     * Determine whether the user can view the contacts.
     *
     * @param User $user
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the contact.
     *
     * @param User    $user
     * @param Contact $contact
     *
     * @return bool
     */
    public function view(User $user, Contact $contact)
    {
        return false;
    }

    /**
     * Determine whether the user can create contacts.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the contact.
     *
     * @param User    $user
     * @param Contact $contact
     *
     * @return bool
     */
    public function update(User $user, Contact $contact)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the contact.
     *
     * @param User    $user
     * @param Contact $contact
     *
     * @return bool
     */
    public function delete(User $user, Contact $contact)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the contact.
     *
     * @param User    $user
     * @param Contact $contact
     *
     * @return bool
     */
    public function restore(User $user, Contact $contact)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the contact.
     *
     * @param User    $user
     * @param Contact $contact
     *
     * @return bool
     */
    public function forceDelete(User $user, Contact $contact)
    {
        return false;
    }
}
