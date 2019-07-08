<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use App\Models\PublicAlbum;
use Illuminate\Http\Response;
use Modules\Album\Entities\Album;
use App\Http\Controllers\Controller;
use Modules\Album\Transformers\AlbumShowResource;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\Album\Transformers\AlbumIndexResource;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $albums = PublicAlbum::latest()->paginate(10);

        return view('albums.index', [
            'albums' => AlbumIndexResource::collection($albums),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  PublicAlbum  $album
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show(PublicAlbum $album)
    {
        $album->load(['cosplayers.media']);
        $this->authorize('view', $album);

        return view('albums.show', [
            'albumResource' => new AlbumShowResource($album),
            'album' => $album,
        ]);
    }
}
