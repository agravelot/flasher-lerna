<?php

namespace App\Observers;

use App\Models\Album;

class AlbumObserver extends ActivityObserverBase
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
}
