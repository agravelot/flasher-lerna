<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Testimonials\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Testimonials\Transformers\TestimonialResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Testimonials\Http\Requests\AdminUpdateTestimonialRequest;

class AdminTestimonialsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $testimonials = Testimonial::paginate();

        return TestimonialResource::collection($testimonials);
    }

    /**
     * Show the specified resource.
     */
    public function show(Testimonial $testimonial): TestimonialResource
    {
        return new TestimonialResource($testimonial);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUpdateTestimonialRequest $request, Testimonial $testimonial): TestimonialResource
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
     *
     * @throws Exception
     */
    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();

        return response()->json(null, 204);
    }
}
