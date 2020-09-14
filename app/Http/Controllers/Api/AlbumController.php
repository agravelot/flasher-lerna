<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumIndexResource;
use App\Http\Resources\AlbumShowResource;
use App\Models\Album;
use App\Models\PublicAlbum;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
                ->allowedFilters(AllowedFilter::exact('categories.id'))
                ->allowedFilters(AllowedFilter::exact('cosplayers.id'))
                ->with('media', 'categories')
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
