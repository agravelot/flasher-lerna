<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use App\Models\GoldenBookPost;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\PublishedGoldenBookPost;
use App\Http\Requests\GoldenBookRequest;

class GoldenBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $goldenBooksPosts = PublishedGoldenBookPost::latest()->paginate(10);

        return view('goldenbook.index', compact('goldenBooksPosts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('goldenbook.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GoldenBookRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(GoldenBookRequest $request): RedirectResponse
    {
        GoldenBookPost::create($request->validated());

        return redirect()->route('goldenbook.index')
            ->withSuccess('Your message has been added to the golden book');
    }
}
