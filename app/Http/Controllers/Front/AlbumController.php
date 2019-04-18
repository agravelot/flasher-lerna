<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PublicAlbum;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Modules\Album\Transformers\AlbumIndexResource;
use Modules\Album\Transformers\AlbumShowResource;

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
            'albums' => AlbumIndexResource::collection($albums)->response()->getContent(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     *
     * @return Response
     * @throws AuthorizationException
     *
     */
    public function show(string $slug)
    {
        $album = PublicAlbum::with(['cosplayers.media'])
            ->whereSlug($slug)
            ->firstOrFail();
        $this->authorize('view', $album);

        return view('albums.show', [
            'album' => (new AlbumShowResource($album))->response()->getContent(),
        ]);
    }
}
