<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\MediaStream;
use App\Models\Album;

class DownloadAlbumController extends Controller
{
    public function show(Album $album): MediaStream
    {
        set_time_limit(0); // Disable timeout for long download
        $pictures = $album->getMedia(Album::PICTURES_COLLECTION);

        return MediaStream::create($album->zip_file_name)->addMedia($pictures);
    }
}
