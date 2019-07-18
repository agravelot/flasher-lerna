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
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Access\AuthorizationException;

class AdminUserController extends Controller
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
        $this->authorize('viewAny', User::class);
        $users = User::with('cosplayer')->paginate(10);

        return view('admin.users.index', compact('users'));
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
        $this->authorize('create', User::class);

        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @throws AuthorizationException
     *
     * @return RedirectResponse
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        User::create($request->validated());

        return redirect(route('admin.users.index'))
            ->withSuccess('User successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @throws AuthorizationException
     *
     * @return View
     */
    public function show(int $id): View
    {
        $user = User::with('albums.media')->findOrFail($id);
        $this->authorize('view', $user);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @throws AuthorizationException
     *
     * @return View
     */
    public function edit(int $id): View
    {
        $this->authorize('update', User::class);
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $cosplayers = Cosplayer::with('user')->get(['id', 'name']);

        return view('admin.users.edit', [
            'user' => $user,
            'cosplayers' => $cosplayers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int         $id
     *
     * @throws AuthorizationException
     *
     * @return RedirectResponse
     */
    public function update(UserRequest $request, $id): RedirectResponse
    {
        $this->authorize('update', User::class);
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $user->update($request->validated());

        return redirect(route('admin.users.index'))
            ->withSuccess('User successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @throws AuthorizationException
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', User::class);
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();

        return redirect(route('admin.users.index'))
            ->withSuccess('User successfully deleted');
    }
}
