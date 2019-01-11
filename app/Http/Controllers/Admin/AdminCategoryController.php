<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class AdminCategoryController extends Controller
{
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
        $categories = Category::paginate(10);

        return view('admin.categories.index', [
            'categories' => $categories,
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
    public function show(string $slug)
    {
        $category = Category::findBySlugOrFail($slug);
        $this->authorize('view', $category);

        return view('admin.categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $category = Category::findBySlugOrFail($slug);
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        $category = Category::create($request->validated());

        return redirect(route('admin.categories.index'))
            ->withSuccess('Category successfully added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, string $slug)
    {
        $category = Category::findBySlugOrFail($slug);
        $this->authorize('update', $category);
        $category->update($request->validated());

        return redirect(route('admin.categories.index'))
            ->withSuccess('Category successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $slug)
    {
        $category = Category::findBySlugOrFail($slug);
        $this->authorize('delete', $category);
        $category->delete();

        return redirect(route('admin.categories.index'))
            ->withSuccess('Category successfully deleted');
    }
}
