<?php

namespace Modules\Album\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\FileAdder\FileAdder;

class AlbumController extends Controller
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

        return AlbumResource::collection(Album::with(['media', 'categories'])
            ->latest()
            ->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AlbumRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AlbumResource
     */
    public function store(AlbumRequest $request)
    {
        $this->authorize('create', Album::class);
        $validated = $request->validated();
        /** @var Album $album */
        $album = Album::create($validated);

        $album->addAllMediaFromRequest()
            ->each(function ($fileAdder) {
                /* @var FileAdder $fileAdder */
                $fileAdder->preservingOriginal()
                    ->withResponsiveImages()
                    ->toMediaCollection('pictures');
            });

        if (Arr::exists($validated, 'categories')) {
            $album->categories()->sync($validated['categories'], false);
        }

        if (Arr::exists($validated, 'cosplayers')) {
            $album->cosplayers()->sync($validated['cosplayers'], false);
        }

        return new AlbumResource($album);
    }

    /**
     * Display the specified resource.
     *
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AlbumResource
     */
    public function show(string $slug)
    {
        $this->authorize('view', Album::class);
        $album = Album::with(['cosplayers.media'])
            ->whereSlug($slug)
            ->firstOrFail();
        $this->authorize('view', $album);

        return new AlbumResource($album);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AlbumRequest $request
     * @param string       $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AlbumResource
     */
    public function update(AlbumRequest $request, string $slug)
    {
        $this->authorize('update', Album::class);
        $album = Album::whereSlug($slug)
            ->firstOrFail();
        $this->authorize('update', $album);
        $validated = $request->validated();
        $album->update($validated);

        // An update can contain no picture
        $key = 'pictures';
        if (Arr::exists($validated, $key)) {
            /* @var Album $album */
            $album->addAllMediaFromRequest()
                ->each(function ($fileAdder) {
                    /* @var FileAdder $fileAdder */
                    $fileAdder->preservingOriginal()
                        ->withResponsiveImages()
                        ->toMediaCollection('pictures');
                });
        }

        if (Arr::exists($validated, 'categories')) {
            $album->categories()->sync($validated['categories'], false);
        }

        if (Arr::exists($validated, 'cosplayers')) {
            $album->cosplayers()->sync($validated['cosplayers'], false);
        }

        return new AlbumResource($album);
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
