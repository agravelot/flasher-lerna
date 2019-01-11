<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Cosplayer;
use App\Models\User;

class AdminUserController extends Controller
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
        $this->authorize('index', User::class);
        $users = User::with('cosplayer')->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
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
        $this->authorize('create', User::class);
        $cosplayers = Cosplayer::with('user')->get(['id', 'name']);

        return view('admin.users.create', [
            'cosplayers' => $cosplayers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);
        $user = User::create($request->validated());

        if ($request->has('cosplayer') && $request->validated()['cosplayer'] !== null) {
            $cosplayerId = $request->validated()['cosplayer'];
            $cosplayer = Cosplayer::findNotLinkedToUser($cosplayerId);
            $user->cosplayer()->save($cosplayer);
        }

        return redirect(route('admin.users.index'))
            ->withSuccess('User successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view', $user);

        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
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
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $user->update($request->validated());

        if ($request->has('cosplayer') && $request->validated()['cosplayer'] !== null) {
            $cosplayer = null;
            $cosplayerId = $request->validated()['cosplayer'];
            if ($cosplayerId > 0) {
                $cosplayer = Cosplayer::findNotLinkedToUser($cosplayerId);
            }
            $user->cosplayer()->save($cosplayer);
        }

        return redirect(route('admin.users.index'))
            ->withSuccess('User successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();

        return redirect(route('admin.users.index'))
            ->withSuccess('User successfully deleted');
    }
}
