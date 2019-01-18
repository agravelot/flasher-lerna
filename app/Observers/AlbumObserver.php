<?php

namespace App\Observers;

use App\Models\Album;

class AlbumObserver
{
    /**
     * Handle the album "creating" event.
     *
     * @param  Album $album
     * @return void
     */
    public function creating(Album $album)
    {
        if ($album->user_id === null) {
            $album->user_id = auth()->id();
        }
    }

    /**
     * Handle the album "created" event.
     *
     * @param Album $album
     */
    public function created(Album $album)
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
     * @param  Album $album
     * @return void
     */
    public function updated(Album $album)
    {
        activity()
            ->performedOn($album)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'updated'])
            ->log('');
    }

    /**
     * Handle the album "deleted" event.
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
