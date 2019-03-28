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
use Illuminate\Support\Arr;
use Modules\Album\Http\Requests\AlbumRequest;
use Modules\Album\Transformers\AlbumIndexResource;
use Modules\Album\Transformers\AlbumShowResource;
use Spatie\QueryBuilder\QueryBuilder;

class AdminAlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $this->authorize('index', Album::class);

        return AlbumIndexResource::collection(
            QueryBuilder::for(Album::class)->with('media')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AlbumRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AlbumIndexResource
     */
    public function store(AlbumRequest $request)
    {
        $this->authorize('create', Album::class);
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
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AlbumShowResource
     */
    public function show(string $slug)
    {
        $this->authorize('view', Album::class);
        $album = Album::with(['cosplayers.media'])
            ->whereSlug($slug)
            ->firstOrFail();
        $this->authorize('view', $album);

        return new AlbumShowResource($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AlbumRequest $request
     * @param string       $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AlbumIndexResource
     */
    public function update(AlbumRequest $request, string $slug)
    {
        $this->authorize('update', Album::class);
        $album = Album::whereSlug($slug)
            ->firstOrFail();
        $this->authorize('update', $album);
        $validated = $request->validated();
        $album->update($validated);

        if (Arr::exists($validated, 'categories')) {
            $album->categories()->sync(collect($validated['categories'])->pluck('id'), true);
        }

        if (Arr::exists($validated, 'cosplayers')) {
            $album->cosplayers()->sync(collect($validated['cosplayers'])->pluck('id'), true);
        }

        return new AlbumShowResource($album);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $slug)
    {
        $this->authorize('delete', Album::class);
        $album = Album::whereSlug($slug)
            ->firstOrFail();
        $this->authorize('delete', $album);
        $album->delete();

        return response()->json(null, 204);
    }
}
