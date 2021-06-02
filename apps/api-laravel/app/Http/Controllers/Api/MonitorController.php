<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MonitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        DB::connection()->getPdo();
        Redis::connection();

        return response()->json([
            'databaseStatus' => 'up',
            'redisStatus' => 'up',
        ]);
    }
}