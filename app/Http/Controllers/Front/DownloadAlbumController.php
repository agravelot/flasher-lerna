<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\PublicAlbum;
use Spatie\MediaLibrary\MediaStream;

class DownloadAlbumController extends Controller
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return MediaStream
     */
    public function show(string $slug)
    {
//        $this->authorize('download', Album::class);
        if (auth()->user()->isAdmin()) {
            $album = Album::findBySlugOrFail($slug);
        } else {
            $album = PublicAlbum::findBySlug($slug);
        }
        $this->authorize('download', $album);
        $pictures = $album->getMedia('pictures');

        return MediaStream::create($album->slug . '.zip')
            ->addMedia($pictures);
    }
}
