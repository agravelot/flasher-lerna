<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Http\Controllers\Api;

use Exception;
use App\Models\Album;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Album\Http\Requests\AlbumRequest;
use Modules\Album\Transformers\AlbumShowResource;
use Modules\Album\Transformers\AlbumIndexResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminAlbumController extends Controller
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
            QueryBuilder::for(Album::class)->allowedFilters('title')
                ->with('media')
                ->withCount('media')
                ->latest()
                ->paginate(15)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AlbumRequest  $request
     *
     * @return AlbumShowResource
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
     *
     * @param  Album  $album
     *
     * @return AlbumShowResource
     */
    public function show(Album $album): AlbumShowResource
    {
        $album->load('categories', 'cosplayers.media');

        return new AlbumShowResource($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AlbumRequest  $request
     * @param  Album  $album
     *
     * @return AlbumShowResource
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
     *
     * @param  Album  $album
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Album $album): JsonResponse
    {
        $album->delete();

        return response()->json(null, 204);
    }
}
