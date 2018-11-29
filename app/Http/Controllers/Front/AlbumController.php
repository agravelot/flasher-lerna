<?php

namespace App\Http\Controllers\Front;

use App\Criteria\PublicAlbumsCriteria;
use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaStream;

class AlbumController extends Controller
{
    /**
     * @var AlbumRepositoryEloquent
     */
    protected $albumRepository;

    /**
     * AlbumController constructor.
     *
     * @param AlbumRepository $albumRepository
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function __construct(AlbumRepository $albumRepository)
    {
        $this->albumRepository = $albumRepository;
        $this->albumRepository->pushCriteria(PublicAlbumsCriteria::class);
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
            $this->albumRepository->popCriteria(PublicAlbumsCriteria::class);
        }

        $albums = $this->albumRepository->with(['media', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('albums.index', ['albums' => $albums]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        //TODO Fix issue with find by slug, policies will check user permission for us
        if (Auth::check() && Auth::user()->isAdmin()) {
            $this->albumRepository->popCriteria(PublicAlbumsCriteria::class);
        }

        $album = $this->albumRepository->findBySlug($slug);
        $this->authorize('view', $album);

        return view('albums.show', ['album' => $album]);
    }

    /**
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return MediaStream
     */
    public function download(string $slug)
    {
        //TODO Fix issue with find by slug, policies will check user permission for us
        if (Auth::check() && Auth::user()->isAdmin()) {
            $this->albumRepository->popCriteria(PublicAlbumsCriteria::class);
        }

        /** @var Album $album */
        $album = $this->albumRepository->findBySlug($slug);
        $this->authorize('download', $album);
        $pictures = $album->getMedia('pictures');

        return MediaStream::create($album->slug.'.zip')->addMedia($pictures);
    }
}
