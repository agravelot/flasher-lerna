<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use App\Models\PublicAlbum;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $albums = PublicAlbum::with('categories')->latest()->paginate(10);

        return view('albums.index', compact('albums'));
    }

    /**
     * Display the specified resource.
     *
     *
     * @throws AuthorizationException
     */
    public function show(PublicAlbum $album): View
    {
        $album->load(['cosplayers.media', 'categories']);
        $this->authorize('view', $album);

        return view('albums.show', compact('album'));
    }
}
