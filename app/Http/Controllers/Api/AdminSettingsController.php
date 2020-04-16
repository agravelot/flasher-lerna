<?php

namespace App\Http\Controllers\Api;

use App\Enums\SettingType;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Http\Resources\UploadMediaCompletedResource;
use App\Http\Resources\UploadMediaProcessingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Routing\Controller;

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
     * @return JsonResponse|UploadMediaProcessingResource|SettingResource
     */
    public function update(Setting $setting, UpdateSettingRequest $request)
    {
        if ($setting->type->value === SettingType::Media) {
            JsonResource::withoutWrapping();

            $setting->value = $request->file();

            return (new UploadMediaCompletedResource($setting->value))->response()->setStatusCode(201);
        }

        $setting->update($request->only('value'));

        return new SettingResource($setting);
    }
}
