<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Http\Controllers\Api;

use App\Models\Album;
use App\Models\PublicAlbum;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Album\Transformers\AlbumShowResource;
use Modules\Album\Transformers\AlbumIndexResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
    public function index(): AnonymousResourceCollection
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
     * @param  PublicAlbum  $album
     *
     * @return AlbumShowResource
     */
    public function show(PublicAlbum $album): AlbumShowResource
    {
        $album->load('categories', 'cosplayers.media');

        return new AlbumShowResource($album);
    }
}
