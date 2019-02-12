<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Observers;

abstract class ActivityObserverBase
{
    /**
     * Handle the album "created" event.
     *
     * @param $album
     */
    public function created($album)
    {
        activity()
            ->performedOn($album)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'created'])
            ->log('');
    }

    /**
     * Handle the album "updated" event.
     *
     * @param  $album
     */
    public function updated($album)
    {
        activity()
            ->performedOn($album)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'updated'])
            ->log('');
    }

    /**
     * Handle the album "deleted" event.
     */
    public function deleted()
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'deleted'])
            ->log('');
    }
}
