<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonials\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\GoldenBookPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\PublishedGoldenBookPost;
use Modules\Testimonials\Transformers\TestimonialResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Testimonials\Http\Requests\AdminUpdateTestimonialRequest;

class AdminTestimonialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $testimonials = PublishedGoldenBookPost::paginate();

        return TestimonialResource::collection($testimonials);
    }

    /**
     * Show the specified resource.
     *
     * @param  GoldenBookPost  $testimonial
     *
     * @return TestimonialResource
     */
    public function show(GoldenBookPost $testimonial): TestimonialResource
    {
        return new TestimonialResource($testimonial);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AdminUpdateTestimonialRequest  $request
     * @param  GoldenBookPost  $testimonial
     *
     * @return TestimonialResource
     */
    public function update(AdminUpdateTestimonialRequest $request, GoldenBookPost $testimonial): TestimonialResource
    {
        $publishedAt = $request->input('published_at');

        if ($publishedAt !== null) {
            $publishedAt = Carbon::parse($request->input('published_at'));
        }

        $testimonial->update(['published_at' => $publishedAt]);

        // TODO Cast published_at to date
        // $testimonial->update($request->validated());

        return new TestimonialResource($testimonial);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  GoldenBookPost  $testimonial
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(GoldenBookPost $testimonial): JsonResponse
    {
        $testimonial->delete();

        return response()->json(null, 204);
    }
}
