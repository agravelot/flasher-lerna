<?php

namespace App\Http\Controllers\Api;

use App\Models\Album;
use App\Models\PublicAlbum;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\AlbumShowResource;
use App\Http\Resources\AlbumIndexResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Album::class);
    }

    /**
     * Display a listing of the resource.
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
     */
    public function show(PublicAlbum $album): AlbumShowResource
    {
        $album->load('categories', 'cosplayers.media');

        return new AlbumShowResource($album);
    }
}
