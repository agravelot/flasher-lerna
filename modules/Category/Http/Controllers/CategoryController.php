<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Category\Transformers\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return CategoryResource::collection(Category::all());
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
}
