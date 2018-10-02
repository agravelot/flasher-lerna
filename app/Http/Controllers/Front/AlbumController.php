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
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function __construct(AlbumRepository $repository)
    {
        $this->repository = $repository;
        $this->repository->pushCriteria(PublicAlbumsCriteria::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO Show user own albums
        if (Auth::check() && Auth::user()->isAdmin()) {
            $this->repository->popCriteria(PublicAlbumsCriteria::class);
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
        //TODO Fix issue with find by slug, policies will check user permission for us
        $this->repository->popCriteria(PublicAlbumsCriteria::class);

        $album = $this->repository->findBySlug($slug);
        $this->authorize('view', $album);
        return view('albums.show', ['album' => $album]);
    }
}
