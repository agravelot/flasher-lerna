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
use Illuminate\Http\Response;
use Modules\Album\Transformers\AlbumIndexResource;

class CosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $cosplayers = Cosplayer::with('media')->get();

        return view('cosplayers.index', compact('cosplayers'));
    }

    /**
     * Display the specified resource.
     *
     * @param Cosplayer $cosplayer
     *
     * @return Response
     */
    public function show(Cosplayer $cosplayer)
    {
        $albums = AlbumIndexResource::collection($cosplayer->load('publicAlbums.media')->publicAlbums);

        return view('cosplayers.show', compact('cosplayer', 'albums'));
    }
}
