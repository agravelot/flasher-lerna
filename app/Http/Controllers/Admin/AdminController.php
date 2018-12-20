<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\ContactRepositoryEloquent;
use App\Repositories\Contracts\AlbumRepository;
use App\Repositories\Contracts\ContactRepository;
use App\Repositories\Contracts\CosplayerRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\CosplayerRepositoryEloquent;
use App\Repositories\UserRepositoryEloquent;

class AdminController extends Controller
{
    /**
     * @var UserRepositoryEloquent
     */
    protected $userRepository;
    /**
     * @var ContactRepositoryEloquent
     */
    protected $contactRepository;
    /**
     * @var AlbumRepositoryEloquent
     */
    protected $albumRepository;
    /**
     * @var CosplayerRepositoryEloquent
     */
    protected $cosplayerRepository;

    /**
     * AdminController constructor.
     */
    public function __construct(
        UserRepository $userRepository,
        ContactRepository $contactRepository,
        CosplayerRepository $cosplayerRepository,
        AlbumRepository $albumRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->contactRepository = $contactRepository;
        $this->albumRepository = $albumRepository;
        $this->cosplayerRepository = $cosplayerRepository;
    }

    /**
     * Display dashboard.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $this->authorize('dashboard');

        $userCount = $this->userRepository->count();
        $albumCount = $this->albumRepository->count();
        $cosplayerCount = $this->cosplayerRepository->count();
        $contactCount = $this->contactRepository->count();

        return view('admin.dashboard.dashboard', [
            'userCount' => $userCount,
            'albumCount' => $albumCount,
            'cosplayerCount' => $cosplayerCount,
            'contactCount' => $contactCount,
        ]);
    }
}
