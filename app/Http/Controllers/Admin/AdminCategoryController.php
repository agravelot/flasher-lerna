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
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     *
     * @return Response
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
     * @param Category $category
     *
     * @throws AuthorizationException
     *
     * @return Response
     */
    public function show(Category $category)
    {
        $this->authorize('view', Category::class);
        $category->load(['publishedAlbums.media', 'publishedAlbums.categories']);
        $this->authorize('view', $category);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     *
     * @throws AuthorizationException
     *
     * @return Response
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
     * @throws AuthorizationException
     *
     * @return Response
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
     * @throws AuthorizationException
     *
     * @return Response
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
     * @throws AuthorizationException
     *
     * @return Response
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
     * @throws AuthorizationException
     * @throws Exception
     *
     * @return RedirectResponse
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
