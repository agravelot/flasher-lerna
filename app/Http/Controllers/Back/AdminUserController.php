<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\Facades\Redirect;

class AdminUserController extends Controller
{
    protected $repository;

    /**
     * AdminCosplayerController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->repository = $userRepository;
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
        $users = $this->repository->paginate(10);

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
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);
        $user = $this->repository->create($request->all());
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
        $user = $this->repository->find($id);
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
        $user = $this->repository->find($id);
        $this->authorize('update', $user);
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->repository->find($id);
        $this->authorize('update', $user);
        $this->repository->update($request->all(), $id);

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
        $this->repository->delete($id);
        return Redirect::back()->withSuccess('User successfully deleted');
    }
}
