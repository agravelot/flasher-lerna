<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cosplayer;

class CosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cosplayers = Cosplayer::with('media')->get();

        return view('cosplayers.index', compact('cosplayers'));
    }

    /**
     * Display the specified resource.
     *
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $cosplayer = Cosplayer::with(['publicAlbums.media', 'publicAlbums.categories'])->whereSlug($slug)->firstOrFail();

        return view('cosplayers.show', compact('cosplayer'));
    }
}
