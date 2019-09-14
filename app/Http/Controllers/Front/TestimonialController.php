<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use Illuminate\View\View;
use App\Models\Testimonial;
use App\Http\Controllers\Controller;
use App\Models\PublishedTestimonial;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TestimonialRequest;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $goldenBooksPosts = PublishedTestimonial::latest()->paginate(10);

        return view('testimonials.index', compact('goldenBooksPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TestimonialRequest $request): RedirectResponse
    {
        Testimonial::create($request->validated());

        return redirect()->route('testimonials.index')
            ->withSuccess(__('Your message has been added to the golden book'));
    }
}
