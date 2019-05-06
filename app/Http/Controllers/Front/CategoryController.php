<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use App\Models\Category;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     *
     * @return Response
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }
}
