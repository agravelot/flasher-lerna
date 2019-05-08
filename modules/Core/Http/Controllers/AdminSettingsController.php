<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Arcanedev\LaravelSettings\Models\Setting;

class AdminSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = settings()->all();

        return response()->json($settings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        return settings()->set([$request->get('key') => $request->get('value')])->save();
    }

    /**
     * Show the specified resource.
     *
     * @param Setting $setting
     *
     * @return Setting
     */
    public function show(Setting $setting)
    {
        return $setting;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Setting $setting
     *
     * @return Response
     */
    public function update(Request $request, Setting $setting)
    {
        return settings()->set([$setting->key => $request->get('value')])->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Setting $setting
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return response()->json(null, 204);
    }
}
