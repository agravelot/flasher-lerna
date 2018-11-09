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
use Exception;

class AdminAlbumController extends Controller
{
    /**
     * @var AlbumRepositoryEloquent
     */
    protected $albumRepository;

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
     * @param CategoryRepository $categoryRepository
     * @param CosplayerRepository $cosplayerRepository
     */
    public function __construct(AlbumRepository $albumRepository,
                                CategoryRepository $categoryRepository,
                                CosplayerRepository $cosplayerRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->albumRepository = $albumRepository;
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
        $albums = $this->albumRepository->with('media')
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
     * @throws \Exception
     */
    public function store(AlbumRequest $request)
    {
        $this->authorize('create', Album::class);

        $validated = $request->validated();
        /** @var Album $album */
        $album = $this->albumRepository->create($validated);

        $album->addMediaFromRequest('pictures')
            ->preservingOriginal()
            ->withResponsiveImages()
            ->toMediaCollection('pictures');

        if (array_key_exists('categories', $validated)) {
            $categoriesIds = $validated['categories'];
            $categories = $this->categoryRepository->findWhereIn('id', $categoriesIds);
            $this->categoryRepository->saveRelation($categories, $album);
        }

        if (array_key_exists('cosplayers', $validated)) {
            $cosplayersIds = $validated['cosplayers'];
            $cosplayers = $this->cosplayerRepository->findWhereIn('id', $cosplayersIds);
            $this->cosplayerRepository->saveRelation($cosplayers, $album);
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
     * @throws \Exception
     */
    public function update(AlbumRequest $request, $id)
    {
        $validated = $request->validated();
        $album = $this->albumRepository->find($id);
        $this->authorize('update', $album);
        $album = $this->albumRepository->update($validated, $id);

        // An update can contain no picture
        $key = 'pictures';
        if (array_key_exists($key, $validated)) {
            foreach ($validated[$key] as $picture) {
                $album
                    ->addMedia($picture)
                    ->preservingOriginal()
                    ->withResponsiveImages()
                    ->toMediaCollection('pictures');
            }
        }

        $key = 'categories';
        if (array_key_exists($key, $validated)) {
            $categoriesIds = $validated[$key];
            $categories = $this->categoryRepository->findWhereIn('id', $categoriesIds);
            $this->categoryRepository->saveRelation($categories, $album);
        }

        $key = 'cosplayers';
        if (array_key_exists($key, $validated)) {
            $cosplayersIds = $validated[$key];
            $cosplayers = $this->cosplayerRepository->findWhereIn('id', $cosplayersIds);
            $this->cosplayerRepository->saveRelation($cosplayers, $album);
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
        $album = $this->albumRepository->findBySlug($slug);
        $this->authorize('delete', $album);

        $this->albumRepository->delete($album->id);

        return back()->withSuccess('Album successfully deleted');
    }
}
