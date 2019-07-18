<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use App\MediaStream;
use App\Models\Album;
use App\Http\Controllers\Controller;

class DownloadAlbumController extends Controller
{
    /**
     * @param Album $album
     * @return MediaStream
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Album $album)
    {
        set_time_limit(0); // Disable timeout for long download
        $this->authorize('download', $album);
        $pictures = $album->getMedia(Album::PICTURES_COLLECTION);

        return MediaStream::create($album->title.'.zip')->addMedia($pictures);
    }
}
