<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;

class ContactPolicy extends Policy
{
    /**
     * Determine whether the user can view the contacts.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the contact.
     *
     * @param \App\Models\User $user
     * @param Contact          $contact
     *
     * @return mixed
     */
    public function view(User $user, Contact $contact)
    {
        return false;
    }

    /**
     * Determine whether the user can create contacts.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the contact.
     *
     * @param \App\Models\User $user
     * @param Contact          $contact
     *
     * @return mixed
     */
    public function update(User $user, Contact $contact)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the contact.
     *
     * @param \App\Models\User $user
     * @param Contact          $contact
     *
     * @return mixed
     */
    public function delete(User $user, Contact $contact)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the contact.
     *
     * @param \App\Models\User $user
     * @param Contact          $contact
     *
     * @return mixed
     */
    public function restore(User $user, Contact $contact)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the contact.
     *
     * @param \App\Models\User $user
     * @param Contact          $contact
     *
     * @return mixed
     */
    public function forceDelete(User $user, Contact $contact)
    {
        return false;
    }
}
