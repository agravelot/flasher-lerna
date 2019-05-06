<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use App\Http\Requests\AlbumRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Spatie\MediaLibrary\FileAdder\FileAdder;
use Illuminate\Auth\Access\AuthorizationException;

class AdminAlbumController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Album::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     *
     * @return Response
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
     * @throws AuthorizationException
     *
     * @return Response
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
     * @param AlbumRequest $request
     *
     * @throws AuthorizationException
     *
     * @return Response
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

        return redirect(route('admin.albums.index'))
            ->withSuccess('Album successfully created');
    }

    /**
     * Display the specified resource.
     *
     *
     * @param Album $album
     *
     * @return Response
     */
    public function show(Album $album)
    {
        return view('admin.albums.show', compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Album $album
     *
     * @return Response
     */
    public function edit(Album $album)
    {
        $categories = Category::all();
        $cosplayers = Cosplayer::all();
        $currentDate = Carbon::now();

        return view('admin.albums.edit', compact('album', 'categories', 'cosplayers', 'currentDate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AlbumRequest $request
     * @param Album        $album
     *
     * @return Response
     */
    public function update(AlbumRequest $request, Album $album)
    {
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

        return redirect(route('admin.albums.index'))
            ->withSuccess('Album successfully updated');
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

        return redirect(route('admin.albums.index'))
            ->withSuccess('Album successfully deleted');
    }
}
