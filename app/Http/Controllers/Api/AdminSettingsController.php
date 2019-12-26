<?php

namespace App\Http\Controllers\Api;

use App\Enums\SettingType;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\CompleteUploadPictureResource;
use App\Http\Resources\ProcessingUploadPictureResource;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Routing\Controller;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

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
     * @return JsonResponse|ProcessingUploadPictureResource|SettingResource
     *
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
