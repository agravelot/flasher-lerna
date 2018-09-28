<?php

namespace App\Http\Controllers\Front;

use App\Criteria\PublicAlbumsCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    /**
     * @var AlbumRepositoryEloquent
     */
    protected $repository;

    /**
     * AlbumController constructor.
     * @param AlbumRepository $repository
     */
    public function __construct(AlbumRepository $repository)
    {
        $this->repository = $repository;
//        $this->repository->pushCriteria(PublicAlbumsCriteria::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index()
    {
        If (!Auth::check() || !Auth::user()->isAdmin()) {
            $this->repository->pushCriteria(PublicAlbumsCriteria::class);
        }

        $albums = $this->repository->with(['pictures', 'categories'])
            ->orderBy('created_at')
            ->all();

        return view('albums.index', ['albums' => $albums]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $slug)
    {
        $album = $this->repository->findBySlug($slug);
        $this->authorize('view', $album);
        return view('albums.show', ['album' => $album]);
    }
}
