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
     */
    public function __construct(CosplayerRepository $repository)
    {
        $this->repository = $repository;
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
        $this->authorize('index', Cosplayer::class);
        $cosplayers = $this->repository->orderBy('updated_at')->paginate(10);

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

        return view('admin.cosplayers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CosplayerRequest $request)
    {
        $this->authorize('create', Cosplayer::class);

        $validated = $request->validated();

        $cosplayer = $this->repository->create($validated);

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
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $cosplayer = $this->repository->findBySlug($slug);
        $this->authorize('view', $cosplayer);

        return view('admin.cosplayers.show', ['cosplayer' => $cosplayer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $cosplayer = $this->repository->findBySlug($slug);
        $this->authorize('update', $cosplayer);

        return view('admin.cosplayers.edit', ['cosplayer' => $cosplayer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CosplayerRequest $request, string $slug)
    {
        $validated = $request->validated();
        $cosplayer = $this->repository->findBySlug($slug);
        $this->authorize('update', $cosplayer);

        $cosplayer = $this->repository->update($validated, $cosplayer->id);

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
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug)
    {
        $album = $this->repository->findBySlug($slug);
        $this->authorize('delete', $album);
        $this->repository->delete($album->id);

        return redirect(route('admin.cosplayers.index'))
            ->withSuccess('Cosplayer successfully deleted');
    }
}
