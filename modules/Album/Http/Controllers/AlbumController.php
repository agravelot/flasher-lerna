<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PublicAlbum;
use Modules\Album\Transformers\AlbumIndexResource;
use Modules\Album\Transformers\AlbumShowResource;
use Spatie\QueryBuilder\QueryBuilder;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AlbumIndexResource::collection(
            QueryBuilder::for(PublicAlbum::class)->with('media')->latest()->paginate(10)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     *
     * @return AlbumShowResource
     */
    public function show(string $slug)
    {
        $album = PublicAlbum::with(['media', 'cosplayers.media'])
            ->whereSlug($slug)
            ->firstOrFail();

        return new AlbumShowResource($album);
    }
}
