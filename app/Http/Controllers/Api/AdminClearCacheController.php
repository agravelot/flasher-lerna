<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\ResponseCache\Facades\ResponseCache;

class AdminClearCacheController extends Controller
{
    public function __invoke(): JsonResponse
    {
        ResponseCache::clear();

        return new JsonResponse();
    }
}
