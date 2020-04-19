<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCoverCategoryRequest;
use App\Http\Resources\UploadMediaCompletedResource;
use App\Http\Resources\UploadMediaProcessingResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;

class AdminCoverCategoryController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     *
     * @return UploadMediaCompletedResource|UploadMediaProcessingResource|JsonResponse
     */
    public function store(StoreCoverCategoryRequest $request)
    {
        JsonResource::withoutWrapping();

        $category = Category::findBySlugOrFail($request->get('category_slug'));

        $media = $category->setCover($request->file('file'));

        return new UploadMediaCompletedResource($media);
    }

    /**
     * Delete the cover of the specified category.
     */
    public function destroy(Category $category): JsonResponse
    {
        optional($category->cover)->delete();

        return new JsonResponse(null, 204);
    }
}
