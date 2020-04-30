<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Http\Resources\AlbumIndexResource;
use App\Http\Resources\AlbumShowResource;
use App\Jobs\DeleteAlbum;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class AdminAlbumController extends Controller
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
            QueryBuilder::for(Album::class)->allowedFilters('title')
                ->withCount('media')
                ->latest()
                ->paginate(15)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlbumRequest $request): AlbumShowResource
    {
        $album = Album::create($request->validated());

        if ($request->has('categories')) {
            $album->categories()->sync(
                collect($request->get('categories'))->pluck('id'), false
            );
        }

        if ($request->has('cosplayers')) {
            $album->cosplayers()->sync(
                collect($request->get('cosplayers'))->pluck('id'), false
            );
        }

        return new AlbumShowResource($album);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album): AlbumShowResource
    {
        $album->load('categories', 'cosplayers.media');

        return new AlbumShowResource($album);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Album $album, AlbumRequest $request): AlbumShowResource
    {
        $album->slug = null;
        $album->update($request->validated());

        if ($request->has('categories')) {
            $album->load('categories')->categories()->sync(
                collect($request->get('categories'))->pluck('id'), true
            );
        }

        if ($request->has('cosplayers')) {
            $album->load('cosplayers')->cosplayers()->sync(
                collect($request->get('cosplayers'))->pluck('id'), true
            );
        }

        return new AlbumShowResource($album->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album): JsonResponse
    {
        DeleteAlbum::dispatch($album);

        return response()->json(null, 204);
    }
}
