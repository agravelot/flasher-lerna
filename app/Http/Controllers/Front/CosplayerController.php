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
        $cosplayer = Cosplayer::all();

        return view('cosplayers.index', ['cosplayer' => $cosplayer]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cosplayers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cosplayer $cosplayer
     * @return \Illuminate\Http\Response
     */
    public function edit(Cosplayer $cosplayer)
    {
        return view('cosplayers.edit', ['cosplayer' => $cosplayer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Cosplayer $cosplayer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cosplayer $cosplayer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cosplayer $cosplayer
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Cosplayer $cosplayer)
    {
        $cosplayer->delete();
        return Redirect::back()->withSuccess('Cosplayer successfully deleted');
    }
}
