<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\CosplayerRequest;
use App\Models\Cosplayer;
use App\Repositories\Contracts\CosplayerRepository;
use App\Repositories\CosplayerRepositoryEloquent;

class AdminCosplayerController extends Controller
{
    /**
     * @var CosplayerRepositoryEloquent
     */
    protected $repository;

    /**
     * AdminCosplayerController constructor.
     * @param CosplayerRepository $repository
     */
    public function __construct(CosplayerRepository $repository)
    {
        $this->middleware(['auth', 'verified']);
        $this->repository = $repository;
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
        $cosplayers = $this->repository->orderBy('updated_at')->paginate(10);

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
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CosplayerRequest $request)
    {
        $this->authorize('create', Cosplayer::class);

        $cosplayer = $this->repository->create($request->all());

        return redirect(route('admin.cosplayers.show', ['cosplayer' => $cosplayer]));
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show(string $slug)
    {
        $this->authorize('view', Cosplayer::class);
        $cosplayer = $this->repository->findBySlug($slug);
        return view('admin.cosplayers.show', ['cosplayer' => $cosplayer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit(string $slug)
    {
        $this->authorize('update', Cosplayer::class);
        $cosplayer = $this->repository->findBySlug($slug);
        return view('admin.cosplayers.edit', ['cosplayer' => $cosplayer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CosplayerRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CosplayerRequest $request, $id)
    {
        //TODO Update categories
        $this->authorize('update', Cosplayer::class);
        $cosplayer = $this->repository->update($request->all(), $id);

        return redirect(route('admin.cosplayers.show', ['cosplayer' => $cosplayer]))->withSuccess('Cosplayers successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy(string $slug)
    {
        $this->authorize('delete', Cosplayer::class);
        $id = $this->repository->findBySlug($slug)->id;
        $this->repository->delete($id);
        return back()->withSuccess('Cosplayer successfully deleted');
    }
}
