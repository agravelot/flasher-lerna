<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Core\Entities\Setting;
use Modules\Core\Enums\SettingType;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Core\Transformers\SettingResource;
use App\Transformers\CompleteUploadPictureResource;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Modules\Core\Http\Requests\UpdateSettingRequest;
use App\Transformers\ProcessingUploadPictureResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class AdminSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return SettingResource::collection(Setting::all());
    }

    /**
     * Show the specified resource.
     */
    public function show(Setting $setting): SettingResource
    {
        return new SettingResource($setting);
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @return JsonResponse|ProcessingUploadPictureResource|SettingResource
     * @throws UploadMissingFileException
     */
    public function update(Setting $setting, UpdateSettingRequest $request, FileReceiver $receiver)
    {
        if ($setting->type->value === SettingType::Media) {
            // We are handling file upload
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

            $setting->value = $save->getFile();

            return (new CompleteUploadPictureResource($setting->value))->response()->setStatusCode(201);
        }

        $setting->update($request->only('value'));

        return new SettingResource($setting);
    }
}
