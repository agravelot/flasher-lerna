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
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the contact.
     */
    public function view(User $user, Contact $contact): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create contacts.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the contact.
     */
    public function update(User $user, Contact $contact): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the contact.
     */
    public function delete(User $user, Contact $contact): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the contact.
     */
    public function restore(User $user, Contact $contact): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the contact.
     */
    public function forceDelete(User $user, Contact $contact): bool
    {
        return false;
    }
}
