<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use App\Models\Picture;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
use Illuminate\Support\Facades\Storage;

class AdminAlbumController extends Controller
{
    /**
     * @var AlbumRepositoryEloquent
     */
    protected $repository;

    /**
     * AdminAlbumController constructor.
     * @param AlbumRepository $repository
     */
    public function __construct(AlbumRepository $repository)
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
        $this->authorize('index', Album::class);
        $albums = $this->repository->with('pictures')
            ->orderBy('updated_at')
            ->paginate(10);

        return view('admin.albums.index', [
            'albums' => $albums
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
        $this->authorize('create', Album::class);
        return view('admin.albums.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AlbumRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(AlbumRequest $request)
    {
        $this->authorize('create', Album::class);
        $album = $this->repository->create($request->all());

        //TODO Store categories
        //TODO Move in repository
        foreach ($request->pictures as $uploaderPicture) {
            $picture = new Picture();
            $picture->filename = Storage::disk('uploads')->put('albums/' . $album->id, $uploaderPicture);
            $album->pictures()->save($picture);
        }

        return redirect(route('admin.albums.show', ['album' => $album]));
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
        $album = $this->repository->findBySlug($slug);
        $this->authorize('view', $album);
        return view('admin.albums.show', ['album' => $album]);
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
        $album = $this->repository->findBySlug($slug);
        $this->authorize('update', $album);
        return view('admin.albums.edit', ['album' => $album]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AlbumRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(AlbumRequest $request, $id)
    {
        //TODO Update categories
        //TODO Keep current user id ?

        $this->authorize('update', Album::class);

        $album = $this->repository->update($request->all(), $id);

        //TODO Move in repo
        foreach ($request->pictures as $uploadedPicture) {
            $picture = new Picture();
            $picture->filename = Storage::disk('uploads')->put('albums/' . $album->id, $uploadedPicture);
            $album->pictures()->save($picture);
        }

        return redirect(route('admin.albums.show', ['album' => $album]))->withSuccess('Album successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy(string $slug)
    {
        $album = $this->repository->findBySlug($slug);
        $this->authorize('delete', $album);

        // Suppression des fichiers (dossier)
        Storage::disk('uploads')->deleteDirectory('albums/' . $album->id);
        $album->pictures()->delete();

        $this->repository->delete($album->id);

        return back()->withSuccess('Album successfully deleted');
    }
}
