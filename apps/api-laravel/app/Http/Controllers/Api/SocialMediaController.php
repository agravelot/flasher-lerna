<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\SocialMediaResource;
use App\Models\SocialMedia;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return SocialMediaResource::collection(
            QueryBuilder::for(SocialMedia::class)
                ->allowedFilters('name')->active()->paginate()
        );
    }
}
