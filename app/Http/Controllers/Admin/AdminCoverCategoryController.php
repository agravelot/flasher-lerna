<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\Resource;
use App\Http\Requests\StoreCoverCategoryRequest;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Http\Resources\CompleteUploadPictureResource;
use App\Http\Resources\ProcessingUploadPictureResource;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class AdminCoverCategoryController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     *
     * @return CompleteUploadPictureResource|ProcessingUploadPictureResource|JsonResponse
     * @throws UploadMissingFileException
     */
    public function store(StoreCoverCategoryRequest $request, FileReceiver $receiver)
    {
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        Resource::withoutWrapping();
        $save = $receiver->receive();

        // check if the upload has not finished (in chunk mode it will send smaller files)
        if (! $save->isFinished()) {
            // we are in chunk mode, lets send the current progress
            return new ProcessingUploadPictureResource($save);
        }

        $category = Category::findBySlugOrFail($request->get('category_slug'));

        $media = $category->setCover($save->getFile());

        return new CompleteUploadPictureResource($media);
    }

    /**
     * Delete the cover of the specified category.
     */
    public function destroy(Category $category): JsonResponse
    {
        optional($category->cover)->delete();

        return (new CategoryResource(
            $category->fresh()->load('media')
        ))->response()->setStatusCode(204);
    }
}
