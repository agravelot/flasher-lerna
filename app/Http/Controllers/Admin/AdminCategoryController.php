<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
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

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $this->authorize('view', Category::class);
        $category = Category::with(['albums.media', 'albums.categories'])
            ->whereSlug($slug)
            ->firstOrFail();
        $this->authorize('view', $category);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $this->authorize('update', Category::class);
        $category = Category::findBySlugOrFail($slug);
        $this->authorize('update', $category);

        return view('admin.categories.edit', compact('category'));
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
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        Category::create($request->validated());

        return redirect(route('admin.categories.index'))
            ->withSuccess('Category successfully added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param string          $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, string $slug)
    {
        $this->authorize('update', Category::class);
        $category = Category::findBySlugOrFail($slug);
        $this->authorize('update', $category);
        $category->update($request->validated());

        return redirect(route('admin.categories.index'))
            ->withSuccess('Category successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $slug)
    {
        $this->authorize('delete', Category::class);
        $category = Category::findBySlugOrFail($slug);
        $this->authorize('delete', $category);
        $category->delete();

        return redirect(route('admin.categories.index'))
            ->withSuccess('Category successfully deleted');
    }
}
