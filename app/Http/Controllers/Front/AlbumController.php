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
        $albums = PublicAlbum::with(['media'])
            ->latest()
            ->paginate(10);

        return view('albums.index', [
            'albums' => AlbumIndexResource::collection($albums),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     *
     * @throws AuthorizationException
     *
     * @return Response
     */
    public function show(string $slug)
    {
        $album = PublicAlbum::with(['cosplayers.media'])
            ->whereSlug($slug)
            ->firstOrFail();
        $this->authorize('view', $album);

        return view('albums.show', [
            'album' => new AlbumShowResource($album),
        ]);
    }
}
