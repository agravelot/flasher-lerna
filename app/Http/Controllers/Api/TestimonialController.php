<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $testimonials = Testimonial::published()->paginate();

        return TestimonialResource::collection($testimonials);
    }
}
