<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PublicAlbum;
use Spatie\MediaLibrary\MediaStream;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = PublicAlbum::with(['media', 'categories'])
            ->latest()
            ->paginate(10);

        return view('albums.index', ['albums' => $albums]);
    }

    /**
     * Display the specified resource.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $album = PublicAlbum::findBySlugOrFail($slug);
        $this->authorize('view', $album);

        return view('albums.show', ['album' => $album]);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return MediaStream
     */
    public function download(string $slug)
    {
        $album = PublicAlbum::findBySlugOrFail($slug);
        $this->authorize('download', $album);
        $pictures = $album->getMedia('pictures');

        return MediaStream::create($album->slug . '.zip')->addMedia($pictures);
    }
}
