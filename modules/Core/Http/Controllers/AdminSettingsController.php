<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Entities\Setting;
use Modules\Core\Transformers\SettingResource;
use Modules\Core\Http\Requests\UpdateSettingRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * @param Setting $setting
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
     *
     * @return SettingResource
     */
    public function update(Setting $setting, UpdateSettingRequest $request)
    {
        $setting->update($request->only('value'));

        return new SettingResource($setting);
    }
}
