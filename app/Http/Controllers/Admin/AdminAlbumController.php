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
        $albums = Album::latestWithPagination();

        return view('admin.albums.index', [
            'albums' => $albums,
        ]);
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

        return view('admin.albums.create', [
            'categories' => $categories,
            'cosplayers' => $cosplayers,
            'currentDate' => Carbon::now(),
        ]);
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
            $categoriesIds = $validated['categories'];
            $categories = Category::findWhereIn('id', $categoriesIds);
            Category::saveRelation($categories, $album);
        }

        if (array_key_exists('cosplayers', $validated)) {
            $cosplayersIds = $validated['cosplayers'];
            $cosplayers = Cosplayer::findWhereIn('id', $cosplayersIds);
            Cosplayer::saveRelation($cosplayers, $album);
        }

        return redirect(route('admin.albums.index'));
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
        $album = Album::findBySlugOrFail($slug);
        $this->authorize('view', $album);

        return view('admin.albums.show', ['album' => $album]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $this->authorize('update', Album::class);
        $album = Album::findBySlugOrFail($slug);
        $this->authorize('update', $album);

        return view('admin.albums.edit', [
            'album' => $album,
            'categories' => Category::all(),
            'cosplayers' => Cosplayer::all(),
            'currentDate' => Carbon::now(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AlbumRequest $request, $id)
    {
        $this->authorize('update', Album::class);
        $album = Album::find($id);
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

        $key = 'categories';
        if (array_key_exists($key, $validated)) {
            $categoriesIds = $validated[$key];
            $categories = Category::findWhereIn('id', $categoriesIds);
            Category::saveRelation($categories, $album);
        }

        $key = 'cosplayers';
        if (array_key_exists($key, $validated)) {
            $cosplayersIds = $validated[$key];
            $cosplayers = Cosplayer::findWhereIn('id', $cosplayersIds);
            Cosplayer::saveRelation($cosplayers, $album);
        }

        return redirect(route('admin.albums.show', ['album' => $album]))
            ->withSuccess('Album successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $slug)
    {
        $this->authorize('delete', Album::class);
        $album = Album::findBySlugOrFail($slug);
        $this->authorize('delete', $album);

        $album->delete();

        return redirect(route('admin.albums.index'))
            ->withSuccess('Album successfully deleted');
    }
}
