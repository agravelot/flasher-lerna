<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PublicAlbum;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

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
     * @throws AuthorizationException
     */
    public function show(PublicAlbum $album): View
    {
        $album->load(['cosplayers.media', 'categories']);
        $this->authorize('view', $album);

        return view('albums.show', compact('album'));
    }
}
