<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Category;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CategoryRequest;
use Illuminate\Auth\Access\AuthorizationException;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     *
     * @return View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);
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
     * @return View
     */
    public function show(Category $category): View
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
     * @return View
     */
    public function edit(string $slug): View
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
     * @return View
     */
    public function create(): View
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
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request): RedirectResponse
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
     * @return RedirectResponse
     */
    public function update(CategoryRequest $request, string $slug): RedirectResponse
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
    public function destroy(string $slug): RedirectResponse
    {
        $this->authorize('delete', Category::class);
        $category = Category::findBySlugOrFail($slug);
        $this->authorize('delete', $category);
        $category->delete();

        return redirect(route('admin.categories.index'))
            ->withSuccess('Category successfully deleted');
    }
}
