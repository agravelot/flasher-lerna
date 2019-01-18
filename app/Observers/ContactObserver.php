<?php

namespace App\Observers;

use App\Models\Contact;

class ContactObserver
{
    /**
     * Handle the contact "created" event.
     *
     * @param Contact $contact
     */
    public function created(Contact $contact)
    {
        activity()
            ->performedOn($contact)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'created'])
            ->log('');
    }

    /**
     * Handle the contact "updated" event.
     *
     * @param  Contact $contact
     * @return void
     */
    public function updated(Contact $contact)
    {
        activity()
            ->performedOn($contact)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'updated'])
            ->log('');
    }

    /**
     * Handle the contact "deleted" event.
     *
     * @return void
     */
    public function deleted()
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'deleted'])
            ->log('');
    }
}
