<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cosplayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cosplayers = Cosplayer::all();

        return view('cosplayers.index', ['cosplayers' => $cosplayers]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cosplayer $cosplayer
     * @return \Illuminate\Http\Response
     */
    public function show(Cosplayer $cosplayer)
    {
        return view('cosplayers.show', ['cosplayer' => $cosplayer]);
    }
}
