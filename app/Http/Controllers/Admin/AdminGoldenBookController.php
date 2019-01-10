<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoldenBookPost;
use App\Repositories\ContactRepositoryEloquent;
use App\Repositories\Contracts\GoldenBookRepository;

class AdminGoldenBookController extends Controller
{
    /**
     * @var ContactRepositoryEloquent
     */
    protected $repository;

    /**
     * AdminGoldenBookController constructor.
     */
    public function __construct(GoldenBookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', GoldenBookPost::class);
        $goldenBookPosts = $this->repository->paginate(10);

        return view('admin.goldenbook.index', [
            'goldenBookPosts' => $goldenBookPosts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $goldenBookPost = $this->repository->find($id);
        $this->authorize('view', $goldenBookPost);

        return view('admin.contacts.show', ['contact' => $goldenBookPost]);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $goldenBookPost = $this->repository->find($id);
        $this->authorize('delete', $goldenBookPost);
        $this->repository->delete($goldenBookPost->id);

        return back()->withSuccess('Contact successfully deleted');
    }
}
