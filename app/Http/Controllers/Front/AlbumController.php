<?php

namespace App\Http\Controllers\Front;

use App\Models\Album;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            $albums = Album::with(['pictures', 'categories'])
                ->latest()
                ->get();
        } else {
            $albums = Album::with(['pictures', 'categories'])
                ->latest()
                ->where('publish', true)
                ->where('password', null)
                ->get();
        }
        return view('albums.index', ['albums' => $albums]);
    }

    /**
     * Display the specified resource.
     *
     * @param Album $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        return view('albums.show', ['album' => $album]);
    }
}
