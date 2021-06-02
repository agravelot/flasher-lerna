<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $testimonials = QueryBuilder::for(Testimonial::class)
            ->allowedSorts('published_at')
            ->published()
            ->paginate();

        return TestimonialResource::collection($testimonials);
    }

    public function store(TestimonialRequest $request): TestimonialResource
    {
        $testimonial = Testimonial::create($request->validated());

        return new TestimonialResource($testimonial);
    }
}
