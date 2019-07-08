<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Core\Entities\Setting;
use Modules\Core\Enums\SettingType;
use Illuminate\Http\Resources\Json\Resource;
use Modules\Core\Transformers\SettingResource;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Modules\Core\Http\Requests\UpdateSettingRequest;
use Modules\Album\Transformers\CompleteUploadPictureResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Album\Transformers\ProcessingUploadPictureResource;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class AdminSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return SettingResource::collection(Setting::all());
    }

    /**
     * Show the specified resource.
     *
     * @param  Setting  $setting
     *
     * @return SettingResource
     */
    public function show(Setting $setting)
    {
        return new SettingResource($setting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Setting  $setting
     * @param  UpdateSettingRequest  $request
     * @param  FileReceiver  $receiver
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
