<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Contracts\CosplayerRepository;
use App\Repositories\Contracts\UserRepository;

class AdminUserController extends Controller
{
    protected $userRepository;
    /**
     * @var CosplayerRepository
     */
    protected $cosplayerRepository;

    /**
     * AdminCosplayerController constructor.
     * @param UserRepository $userRepository
     * @param CosplayerRepository $cosplayerRepository
     */
    public function __construct(UserRepository $userRepository, CosplayerRepository $cosplayerRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->cosplayerRepository = $cosplayerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', User::class);
        $users = $this->userRepository->with('cosplayer')->paginate(10);

        return view('admin.users.index', [
            'users' => $users
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
        $this->authorize('create', User::class);
        $cosplayers = $this->cosplayerRepository->with('user')->all(['id', 'name']);
        return view('admin.users.create', [
            'cosplayers' => $cosplayers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);
        $user = $this->userRepository->create($request->validated());
        if ($request->has('cosplayer')) {
            $cosplayerId = $request->validated()['cosplayer'];
            $cosplayer = $this->cosplayerRepository->findNotLinkedToUser($cosplayerId);
            $this->userRepository->setCosplayer($user, $cosplayer);
        }

        return redirect(route('admin.users.show', ['user' => $user]));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $id)
    {
        $user = $this->userRepository->find($id);
        $this->authorize('update', $user);

        $cosplayers = $this->cosplayerRepository->with('user')->all( ['id', 'name']);

        return view('admin.users.edit', [
            'user' => $user,
            'cosplayers' => $cosplayers
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userRepository->find($id);
        $this->authorize('update', $user);
        $user = $this->userRepository->update($request->validated(), $id);
        if ($request->has('cosplayer')) {
            $cosplayer = null;
            $cosplayerId = $request->validated()['cosplayer'];
            if ($cosplayerId > 0) {
                $cosplayer = $this->cosplayerRepository->findNotLinkedToUser($cosplayerId);
            }
            $this->userRepository->setCosplayer($user, $cosplayer);
        }

        return redirect(route('admin.users.show', ['user' => $user]))->withSuccess('Users successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        $this->authorize('delete', User::class);
        $this->userRepository->delete($id);
        return back()->withSuccess('User successfully deleted');
    }
}
