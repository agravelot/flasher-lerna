<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
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
     */
    public function __construct(AlbumRepository $albumRepository)
    {
        $this->albumRepository = $albumRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = $this->albumRepository->latestWithPagination();

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
        $album = $this->albumRepository->findBySlug($slug);
        $this->authorize('download', $album);
        $pictures = $album->getMedia('pictures');

        return MediaStream::create($album->slug . '.zip')->addMedia($pictures);
    }
}
