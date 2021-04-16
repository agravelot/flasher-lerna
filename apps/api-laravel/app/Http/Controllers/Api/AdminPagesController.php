<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;

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
