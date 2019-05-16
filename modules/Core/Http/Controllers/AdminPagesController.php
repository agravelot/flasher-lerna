<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Entities\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Core\Transformers\PageResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return PageResource::collection(Page::all());
    }

    /**
     * Show the specified resource.
     *
     * @param Page $page
     *
     * @return PageResource
     */
    public function show(Page $page)
    {
        return new PageResource($page);
    }

    /**
     * @param  Request  $request
     *
     * @return PageResource
     */
    public function store(Request $request)
    {
        $page = Page::create($request->all());

        return new PageResource($page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Page    $page
     * @param Request $request
     *
     * @return PageResource
     */
    public function update(Page $page, Request $request)
    {
        $page->update($request->only('name', 'title', 'description'));

        return new PageResource($page);
    }

    /**
     * @param Page $page
     *
     * @throws \Exception
     *
     * @return JsonResponse
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return response()->json(null, 204);
    }
}
