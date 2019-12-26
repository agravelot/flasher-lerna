<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cosplayer;
use Illuminate\View\View;

class CosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $cosplayers = Cosplayer::with('media')->orderBy('name')->paginate();

        return view('cosplayers.index', compact('cosplayers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Cosplayer $cosplayer): View
    {
        $albums = $cosplayer->publicAlbums()->paginate();

        return view('cosplayers.show', compact('cosplayer', 'albums'));
    }
}
