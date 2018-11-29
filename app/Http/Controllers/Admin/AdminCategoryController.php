<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepositoryEloquent;
use App\Repositories\Contracts\CategoryRepository;

class AdminCategoryController extends Controller
{
    /**
     * @var CategoryRepositoryEloquent
     */
    protected $repository;

    /**
     * AdminCategoryController constructor.
     *
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->middleware(['auth', 'verified']);
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
        $this->authorize('index', Category::class);
        $categories = $this->repository->paginate(10);

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $category = $this->repository->findBySlug($slug);
        $this->authorize('view', $category);

        return view('admin.categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $category = $this->repository->findBySlug($slug);
        $this->authorize('update', $category);

        return view('admin.categories.edit', ['category' => $category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        $category = $this->repository->create($request->validated());

        return redirect(route('admin.categories.show', ['category' => $category]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param string          $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, string $slug)
    {
        $category = $this->repository->findBySlug($slug);
        $this->authorize('update', $category);
        $category = $this->repository->update($request->validated(), $category->id);

        return redirect(route('admin.categories.show', ['category' => $category]))->withSuccess('Category successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $slug)
    {
        $category = $this->repository->findBySlug($slug);
        $this->authorize('delete', $category);
        $this->repository->delete($category->id);

        return back()->withSuccess('Category successfully deleted');
    }
}
