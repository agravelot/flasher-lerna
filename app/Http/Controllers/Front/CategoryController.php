<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->repository->paginate(10);

        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $category = $this->repository->findBySlug($slug);

        return view('categories.show', ['category' => $category]);
    }
}
