<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CosplayerRequest;
use App\Models\Cosplayer;
use App\Models\User;

class AdminCosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Cosplayer::class);
        $cosplayers = Cosplayer::orderBy('updated_at')->paginate(10);

        return view('admin.cosplayers.index', [
            'cosplayers' => $cosplayers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Cosplayer::class);
        $users = User::with('cosplayer')->get(['id', 'name']);

        return view('admin.cosplayers.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CosplayerRequest $request)
    {
        $this->authorize('create', Cosplayer::class);
        $validated = $request->validated();
        $cosplayer = Cosplayer::create($validated);

        $key = 'avatar';
        if (array_key_exists($key, $validated)) {
            $cosplayer
                ->addMedia($validated[$key])
                ->preservingOriginal()
                ->withResponsiveImages()
                ->toMediaCollection('avatar');
        }

        return redirect(route('admin.cosplayers.index'))
            ->withSuccess('Cosplayer successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $this->authorize('view', Cosplayer::class);
        $cosplayer = Cosplayer::findBySlugOrFail($slug);
        $this->authorize('view', $cosplayer);

        return view('admin.cosplayers.show', ['cosplayer' => $cosplayer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $this->authorize('update', Cosplayer::class);
        $cosplayer = Cosplayer::findBySlugOrFail($slug);
        $this->authorize('update', $cosplayer);
        $users = User::with('cosplayer')->get(['id', 'name']);

        return view('admin.cosplayers.edit', compact('cosplayer', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CosplayerRequest $request, string $slug)
    {
        $this->authorize('update', Cosplayer::class);
        $cosplayer = Cosplayer::findBySlugOrFail($slug);
        $this->authorize('update', $cosplayer);

        $validated = $request->validated();
        $cosplayer->update($validated);

        $key = 'avatar';
        if (array_key_exists($key, $validated)) {
            $cosplayer
                ->addMedia($validated[$key])
                ->preservingOriginal()
                ->withResponsiveImages()
                ->toMediaCollection('avatar');
        }

        return redirect(route('admin.cosplayers.index'))
            ->withSuccess('Cosplayer successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug)
    {
        $this->authorize('delete', Cosplayer::class);
        $cosplayer = Cosplayer::findBySlugOrFail($slug);
        $this->authorize('delete', $cosplayer);
        $cosplayer->delete();

        return redirect(route('admin.cosplayers.index'))
            ->withSuccess('Cosplayer successfully deleted');
    }
}
