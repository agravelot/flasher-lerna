<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\PublicAlbum;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Album\Transformers\AlbumIndexResource;
use Modules\Album\Transformers\AlbumShowResource;
use Spatie\QueryBuilder\QueryBuilder;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Album::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return AlbumIndexResource::collection(
            QueryBuilder::for(PublicAlbum::class)
                ->with('media')
                ->latest()
                ->paginate(10)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param PublicAlbum $album
     *
     * @return AlbumShowResource
     */
    public function show(PublicAlbum $album)
    {
        $album->load('categories', 'cosplayers.media');

        return new AlbumShowResource($album);
    }
}
