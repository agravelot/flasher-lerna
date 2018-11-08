<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\GoldenBookPost;
use App\Repositories\ContactRepositoryEloquent;
use App\Repositories\Contracts\ContactRepository;
use App\Repositories\Contracts\GoldenBookRepository;

class AdminGoldenBookController extends Controller
{
    /**
     * @var ContactRepositoryEloquent
     */
    protected $repository;

    /**
     * AdminGoldenBookController constructor.
     * @param GoldenBookRepository $repository
     */
    public function __construct(GoldenBookRepository $repository)
    {
        $this->middleware(['auth', 'verified']);
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', GoldenBookPost::class);
        $goldenBookPosts = $this->repository->paginate(10);

        return view('admin.goldenbook.index', [
            'goldenBookPosts' => $goldenBookPosts
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        $goldenBookPost = $this->repository->find($id);
        $this->authorize('delete', $goldenBookPost);
        $this->repository->delete($goldenBookPost->id);
        return back()->withSuccess('Contact successfully deleted');
    }
}
