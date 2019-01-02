<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

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
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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
