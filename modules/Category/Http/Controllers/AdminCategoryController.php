<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Category\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Category\Http\Requests\CategoryRequest;
use Modules\Category\Transformers\CategoryResource;
use Modules\Cosplayer\Transformers\CosplayerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return CosplayerResource::collection(
            QueryBuilder::for(Category::class)->allowedFilters('name')
                ->paginate(15)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     *
     * @return CategoryResource
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Show the specified resource.
     *
     * @param Category $category
     *
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param Category        $category
     *
     * @return CategoryResource
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->slug = null;
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
    }
}
