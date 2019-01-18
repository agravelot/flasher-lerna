<?php

namespace App\Observers;

use App\Models\Cosplayer;

class CosplayerObserver
{
    /**
     * Handle the cosplayer "created" event.
     *
     * @param Cosplayer $cosplayer
     */
    public function created(Cosplayer $cosplayer)
    {
        activity()
            ->performedOn($cosplayer)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'created'])
            ->log('');
    }

    /**
     * Handle the cosplayer "updated" event.
     *
     * @param  Cosplayer $cosplayer
     * @return void
     */
    public function updated(Cosplayer $cosplayer)
    {
        activity()
            ->performedOn($cosplayer)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'updated'])
            ->log('');
    }

    /**
     * Handle the cosplayer "deleted" event.
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
