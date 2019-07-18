<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Observers;

use App\Models\Album;

class AlbumObserver extends ActivityObserverBase
{
    /**
     * Handle the album "creating" event.
     *
     * @param Album $album
     *
     * @return void
     */
    public function creating(Album $album): void
    {
        if ($album->user_id === null) {
            $album->user_id = auth()->id();
        }
    }
}
