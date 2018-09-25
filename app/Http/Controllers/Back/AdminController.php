<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Repositories\AlbumRepositoryEloquent;
use App\Repositories\ContactRepositoryEloquent;
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
     * @param UserRepositoryEloquent $userRepository
     * @param ContactRepositoryEloquent $contactRepository
     * @param CosplayerRepositoryEloquent $cosplayerRepository
     * @param AlbumRepositoryEloquent $albumRepository
     */
    public function __construct(
        UserRepositoryEloquent $userRepository,
        ContactRepositoryEloquent $contactRepository,
        CosplayerRepositoryEloquent $cosplayerRepository,
        AlbumRepositoryEloquent $albumRepository)
    {
        $this->middleware(['auth', 'verified']);
        $this->userRepository = $userRepository;
        $this->contactRepository = $contactRepository;
        $this->albumRepository = $albumRepository;
        $this->cosplayerRepository = $cosplayerRepository;
    }

    /**
     * Display dashboard
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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
            'contactCount' => $contactCount
        ]);
    }
}
