<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Cosplayer;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\Cosplayer\Http\Requests\CosplayerRequest;

class AdminCosplayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     *
     * @return View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Cosplayer::class);
        $cosplayers = Cosplayer::paginate(10);

        return view('admin.cosplayers.index', [
            'cosplayers' => $cosplayers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     *
     * @return View
     */
    public function create(): View
    {
        $this->authorize('create', Cosplayer::class);
        $users = User::with('cosplayer')->get(['id', 'name']);

        return view('admin.cosplayers.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CosplayerRequest $request
     *
     * @throws AuthorizationException
     *
     * @return RedirectResponse
     */
    public function store(CosplayerRequest $request): RedirectResponse
    {
        $this->authorize('create', Cosplayer::class);
        $cosplayer = Cosplayer::create($request->validated());

        if ($request->has('avatar')) {
            $cosplayer->avatar = $request->file('avatar');
        }

        return redirect(route('admin.cosplayers.index'))
            ->withSuccess('Cosplayer successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     *
     * @throws AuthorizationException
     *
     * @return View
     */
    public function show(string $slug): View
    {
        $this->authorize('view', Cosplayer::class);
        $cosplayer = Cosplayer::findBySlugOrFail($slug);
        $cosplayer->load('publicAlbums.categories');
        $albums = $cosplayer->load('publicAlbums.media')->publicAlbums()->paginate();
        $this->authorize('view', $cosplayer);

        return view('admin.cosplayers.show', compact('cosplayer', 'albums'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     *
     * @throws AuthorizationException
     *
     * @return View
     */
    public function edit(string $slug): View
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
     * @param CosplayerRequest $request
     * @param string           $slug
     *
     * @throws AuthorizationException
     *
     * @return RedirectResponse
     */
    public function update(CosplayerRequest $request, string $slug): RedirectResponse
    {
        $this->authorize('update', Cosplayer::class);
        $cosplayer = Cosplayer::findBySlugOrFail($slug);
        $this->authorize('update', $cosplayer);

        $cosplayer->update($request->validated());

        if ($request->has('avatar')) {
            $cosplayer->avatar = $request->file('avatar');
        }

        return redirect(route('admin.cosplayers.index'))
            ->withSuccess('Cosplayer successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     *
     * @throws AuthorizationException
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function destroy(string $slug): RedirectResponse
    {
        $this->authorize('delete', Cosplayer::class);
        $cosplayer = Cosplayer::findBySlugOrFail($slug);
        $this->authorize('delete', $cosplayer);
        $cosplayer->delete();

        return redirect(route('admin.cosplayers.index'))
            ->withSuccess('Cosplayer successfully deleted');
    }
}
