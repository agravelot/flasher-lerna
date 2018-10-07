<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\CategoryRepositoryEloquent;
use App\Repositories\Contracts\CategoryRepository;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryEloquent
     */
    protected $repository;

    /**
     * AdminCategoryController constructor.
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
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', Category::class);
        $categories = $this->repository->paginate(10);

        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show(string $slug)
    {
        $category = $this->repository->findBySlug($slug);
        $this->authorize('view', $category);
        return view('categories.show', ['category' => $category]);
    }

}
