<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use App\Models\Cosplayer;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class CosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $cosplayers = Cosplayer::with('media')->get();

        return view('cosplayers.index', compact('cosplayers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Cosplayer $cosplayer): View
    {
        $albums = $cosplayer->load('publicAlbums.media')->publicAlbums()->paginate();

        return view('cosplayers.show', compact('cosplayer', 'albums'));
    }
}
