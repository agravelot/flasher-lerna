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
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Modules\Album\Http\Requests\AlbumRequest;
use Modules\Album\Transformers\AlbumIndexResource;
use Modules\Album\Transformers\AlbumShowResource;
use Spatie\QueryBuilder\QueryBuilder;

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
    public function index()
    {
        return AlbumIndexResource::collection(
            QueryBuilder::for(Album::class)->with('media')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AlbumRequest $request
     *
     * @return AlbumShowResource
     */
    public function store(AlbumRequest $request)
    {
        $validated = $request->validated();
        /** @var Album $album */
        $album = Album::create($validated);

        if (Arr::exists($validated, 'categories')) {
            $album->categories()->sync(collect($validated['categories'])->pluck('id'), false);
        }

        if (Arr::exists($validated, 'cosplayers')) {
            $album->cosplayers()->sync(collect($validated['cosplayers'])->pluck('id'), false);
        }

        return new AlbumShowResource($album);
    }

    /**
     * Display the specified resource.
     *
     * @param Album $album
     *
     * @return AlbumShowResource
     */
    public function show(Album $album)
    {
        $album->load('categories', 'cosplayers.media');

        return new AlbumShowResource($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AlbumRequest $request
     * @param Album        $album
     *
     * @return AlbumShowResource
     */
    public function update(Album $album, AlbumRequest $request)
    {
        $validated = $request->validated();
        $album->update($validated);

        if (Arr::exists($validated, 'categories')) {
            $album->load('categories')->categories()->sync(
                collect($validated['categories'])->pluck('id'), true
            );
        }

        if (Arr::exists($validated, 'cosplayers')) {
            $album->load('cosplayers')->cosplayers()->sync(
                collect($validated['cosplayers'])->pluck('id'), true
            );
        }

        return new AlbumShowResource($album);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Album $album
     *
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function destroy(Album $album)
    {
        $album->delete();

        return response()->json(null, 204);
    }
}
