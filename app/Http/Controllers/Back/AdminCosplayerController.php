<?php

namespace App\Http\Controllers\Back;

use App\Http\Requests\CosplayerRequest;
use App\Models\Cosplayer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class AdminCosplayerController extends Controller
{
    /**
     * AdminCosplayerController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', Cosplayer::class);
        $cosplayers = Cosplayer::latest()->get();

        return view('admin.cosplayers.index', [
            'cosplayers' => $cosplayers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Cosplayer::class);
        return view('admin.cosplayers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CosplayerRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CosplayerRequest $request)
    {
        $cosplayer = new Cosplayer();
        $cosplayer->name = $request->input('name');

        $this->authorize('create', $cosplayer);

        $cosplayer->save();

        return redirect(route('admin.cosplayers.show', ['cosplayer' => $cosplayer]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cosplayer $cosplayer
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Cosplayer $cosplayer)
    {
        $this->authorize('view', $cosplayer);
        return view('admin.cosplayers.show', ['cosplayer' => $cosplayer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cosplayer $cosplayer
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Cosplayer $cosplayer)
    {
        $this->authorize('edit', $cosplayer);
        return view('admin.cosplayers.edit', ['cosplayer' => $cosplayer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CosplayerRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CosplayerRequest $request, $id)
    {
        //TODO Update categories
        $cosplayer = Cosplayer::find($id);

        $this->authorize('update', $cosplayer);

        $cosplayer->name = $request->input('name');
        $cosplayer->save();

        return redirect(route('admin.cosplayers.show', ['cosplayer' => $cosplayer]))->withSuccess('Cosplayers successfully updated');
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
        $this->authorize('destroy', $cosplayer);
        $cosplayer->delete();
        return Redirect::back()->withSuccess('Cosplayer successfully deleted');
    }
}
