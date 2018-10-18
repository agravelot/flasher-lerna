<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
use App\Repositories\Contracts\CategoryRepository;
use App\Repositories\Contracts\CosplayerRepository;
use App\Repositories\Contracts\PictureRepository;
use Illuminate\Support\Facades\Storage;

class AdminAlbumController extends Controller
{
    /**
     * @var AlbumRepositoryEloquent
     */
    protected $albumRepository;
    /**
     * @var PictureRepository
     */
    protected $pictureRepository;
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;
    /**
     * @var CosplayerRepository
     */
    protected $cosplayerRepository;

    /**
     * AdminAlbumController constructor.
     * @param AlbumRepository $albumRepository
     * @param PictureRepository $pictureRepository
     * @param CategoryRepository $categoryRepository
     * @param CosplayerRepository $cosplayerRepository
     */
    public function __construct(AlbumRepository $albumRepository,
                                PictureRepository $pictureRepository,
                                CategoryRepository $categoryRepository,
                                CosplayerRepository $cosplayerRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->albumRepository = $albumRepository;
        $this->pictureRepository = $pictureRepository;
        $this->categoryRepository = $categoryRepository;
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
        $this->authorize('index', Album::class);
        $albums = $this->albumRepository->with('pictures')
            ->orderBy('updated_at', 'desc')
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

        $categories = $this->categoryRepository->all();
        $cosplayers = $this->cosplayerRepository->all();

        return view('admin.albums.create', [
            'categories' => $categories,
            'cosplayers' => $cosplayers,
        ]);
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

        $categoriesIds = $request->validated()['categories'];
        $cosplayersIds = $request->validated()['cosplayers'];

        $album = $this->albumRepository->create($request->validated());
        //TODO Récupérer depuis validated
        $this->pictureRepository->createForAlbum($request->files->get('pictures'), $album);

        $categories = $this->categoryRepository->findWhereIn('id', $categoriesIds);
        $cosplayers = $this->cosplayerRepository->findWhereIn('id', $cosplayersIds);

        $this->categoryRepository->saveRelation($categories, $album);
        $this->cosplayerRepository->saveRelation($cosplayers, $album);

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
        $album = $this->albumRepository->findBySlug($slug);
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
        $album = $this->albumRepository->findBySlug($slug);
        $this->authorize('update', $album);

        $categories = $this->categoryRepository->all();
        $cosplayers = $this->cosplayerRepository->all();

        return view('admin.albums.edit', [
            'album' => $album,
            'categories' => $categories,
            'cosplayers' => $cosplayers,
        ]);
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
        $album = $this->albumRepository->update($request->validated(), $id);
        $this->authorize('update', $album);

        //TODO Récupérer depuis validated
        $this->pictureRepository->createForAlbum($request->files->get('pictures'), $album);

        $categoriesIds = $request->validated()['categories'];
        $cosplayersIds = $request->validated()['cosplayers'];

        $categories = $this->categoryRepository->findWhereIn('id', $categoriesIds);
        $cosplayers = $this->cosplayerRepository->findWhereIn('id', $cosplayersIds);

        $this->categoryRepository->saveRelation($categories, $album);
        $this->cosplayerRepository->saveRelation($cosplayers, $album);

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
        $album = $this->albumRepository->findBySlug($slug);
        $this->authorize('delete', $album);

        // Suppression des fichiers (dossier)
        Storage::disk('uploads')->deleteDirectory('albums/' . $album->id);
        $album->pictures()->delete();
        $album->pictureHeader()->delete();

        $this->albumRepository->delete($album->id);

        return back()->withSuccess('Album successfully deleted');
    }
}
