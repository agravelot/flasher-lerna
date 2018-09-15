<?php

namespace App\Http\Controllers\Back;

use App\Models\Cosplayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class AdminCosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cosplayers = Cosplayer::latest()->get();

        return view('admin.cosplayers.index', [
            'cosplayers' => $cosplayers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cosplayers.create');
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cosplayer $cosplayer
     * @return \Illuminate\Http\Response
     */
    public function edit(Cosplayer $cosplayer)
    {
        return view('admin.cosplayers.edit', ['cosplayer' => $cosplayer]);
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
