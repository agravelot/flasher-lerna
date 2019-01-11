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
use App\Models\User;
use App\Repositories\Contracts\CosplayerRepository;
use App\Repositories\Contracts\UserRepository;

class AdminUserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var CosplayerRepository
     */
    protected $cosplayerRepository;

    /**
     * AdminCosplayerController constructor.
     */
    public function __construct(UserRepository $userRepository, CosplayerRepository $cosplayerRepository)
    {
        $this->userRepository = $userRepository;
        $this->cosplayerRepository = $cosplayerRepository;
    }

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
        $users = $this->userRepository->with('cosplayer')->paginate(10);

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
        $cosplayers = $this->cosplayerRepository->with('user')->all(['id', 'name']);

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
        $user = $this->userRepository->create($request->validated());

        if ($request->has('cosplayer') && $request->validated()['cosplayer'] !== null) {
            $cosplayerId = $request->validated()['cosplayer'];
            $cosplayer = $this->cosplayerRepository->findNotLinkedToUser($cosplayerId);
            $this->userRepository->setCosplayer($user, $cosplayer);
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
        $user = $this->userRepository->find($id);
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
        $user = $this->userRepository->find($id);
        $this->authorize('update', $user);

        $cosplayers = $this->cosplayerRepository->with('user')->all(['id', 'name']);

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
        $user = $this->userRepository->find($id);
        $this->authorize('update', $user);
        $user = $this->userRepository->update($request->validated(), $id);

        if ($request->has('cosplayer') && $request->validated()['cosplayer'] !== null) {
            $cosplayer = null;
            $cosplayerId = $request->validated()['cosplayer'];
            if ($cosplayerId > 0) {
                $cosplayer = $this->cosplayerRepository->findNotLinkedToUser($cosplayerId);
            }
            $this->userRepository->setCosplayer($user, $cosplayer);
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
        $user = $this->userRepository->find($id);
        $this->authorize('delete', $user);
        $this->userRepository->delete($user->id);

        return redirect(route('admin.users.index'))
            ->withSuccess('User successfully deleted');
    }
}
