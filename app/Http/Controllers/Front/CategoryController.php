<?php

namespace App\Http\Controllers\Front;

use App\Models\Category;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::with('media')->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        return view('categories.show', compact('category'));
    }
}
