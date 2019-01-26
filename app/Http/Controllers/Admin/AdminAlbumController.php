<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use Carbon\Carbon;
use Spatie\MediaLibrary\FileAdder\FileAdder;

class AdminAlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Album::class);
        $albums = Album::with(['media', 'categories'])
            ->latest()
            ->paginate(10);

        return view('admin.albums.index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Album::class);
        $categories = Category::all();
        $cosplayers = Cosplayer::all();
        $currentDate = Carbon::now();

        return view('admin.albums.create', compact('categories', 'cosplayers', 'currentDate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
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

        if (array_key_exists('categories', $validated)) {
            $album->categories()->sync($validated['categories'], false);
        }

        if (array_key_exists('cosplayers', $validated)) {
            $album->cosplayers()->sync($validated['cosplayers'], false);
        }

        return redirect(route('admin.albums.index'))
            ->withSuccess('Album successfully created');
    }

    /**
     * Display the specified resource.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $this->authorize('view', Album::class);
        $album = Album::with(['cosplayers.media'])
            ->whereSlug($slug)
            ->firstOrFail();
        $this->authorize('view', $album);

        return view('admin.albums.show', compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $this->authorize('update', Album::class);
        $album = Album::whereSlug($slug)->firstOrFail();
        $this->authorize('update', $album);
        $categories = Category::all();
        $cosplayers = Cosplayer::all();
        $currentDate = Carbon::now();

        return view('admin.albums.edit', compact('album', 'categories', 'cosplayers', 'currentDate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
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
        if (array_key_exists($key, $validated)) {
            /* @var Album $album */
            $album->addAllMediaFromRequest()
                ->each(function ($fileAdder) {
                    /* @var FileAdder $fileAdder */
                    $fileAdder->preservingOriginal()
                        ->withResponsiveImages()
                        ->toMediaCollection('pictures');
                });
        }

        if (array_key_exists('categories', $validated)) {
            $album->categories()->sync($validated['categories'], false);
        }

        if (array_key_exists('cosplayers', $validated)) {
            $album->cosplayers()->sync($validated['cosplayers'], false);
        }

        return redirect(route('admin.albums.index'))
            ->withSuccess('Album successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
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

        return redirect(route('admin.albums.index'))
            ->withSuccess('Album successfully deleted');
    }
}
