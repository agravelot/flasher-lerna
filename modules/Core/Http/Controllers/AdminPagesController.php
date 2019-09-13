<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Entities\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Core\Transformers\PageResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return PageResource::collection(
            QueryBuilder::for(Page::class)->allowedFilters('name')->paginate(15)
        );
    }

    /**
     * Show the specified resource.
     */
    public function show(Page $page): PageResource
    {
        return new PageResource($page);
    }

    public function store(Request $request): PageResource
    {
        $page = Page::create($request->all());

        return new PageResource($page);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Page $page, Request $request): PageResource
    {
        $page->update($request->only('name', 'title', 'description'));

        return new PageResource($page);
    }

    /**
     * @throws Exception
     */
    public function destroy(Page $page): JsonResponse
    {
        $page->delete();

        return response()->json(null, 204);
    }
}
