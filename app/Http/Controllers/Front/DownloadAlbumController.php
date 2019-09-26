<?php

namespace App\Http\Controllers\Front;

use App\MediaStream;
use App\Models\Album;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class DownloadAlbumController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function show(Album $album): MediaStream
    {
        set_time_limit(0); // Disable timeout for long download
        $this->authorize('download', $album);
        $pictures = $album->getMedia(Album::PICTURES_COLLECTION);

        return MediaStream::create($album->zip_file_name)->addMedia($pictures);
    }
}
