<?php

namespace App\Observers;

use App\Models\Album;

class AlbumObserver extends ActivityObserverBase
{
    /**
     * Handle the album "creating" event.
     */
    public function creating(Album $album): void
    {
        if ($album->user_id === null) {
            $album->user_id = auth()->id();
        }
    }
}
