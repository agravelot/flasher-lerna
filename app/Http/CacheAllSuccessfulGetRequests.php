<?php

namespace App\Http;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests as CacheAllSuccessfulGetRequestsBase;

class CacheAllSuccessfulGetRequests extends CacheAllSuccessfulGetRequestsBase
{
    public function shouldCacheRequest(Request $request): bool
    {
        if ($request->session()->hasOldInput()) {
            return false;
        }

        $flashs = ['errors', 'warning', 'notice', 'success'];

        if ($request->session()->has($flashs)) {
            return false;
        }

        return parent::shouldCacheRequest($request);
    }
}
