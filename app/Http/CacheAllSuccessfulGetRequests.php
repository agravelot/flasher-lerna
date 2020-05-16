<?php

declare(strict_types=1);

namespace App\Http;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests as CacheAllSuccessfulGetRequestsBase;

class CacheAllSuccessfulGetRequests extends CacheAllSuccessfulGetRequestsBase
{
    public function shouldCacheRequest(Request $request): bool
    {
        if ($this->isRequestWithFlash($request)) {
            return false;
        }

        if ($this->isPassportRequest($request)) {
            return false;
        }

        return parent::shouldCacheRequest($request);
    }

    public function isRequestWithFlash(Request $request): bool
    {
        if ($request->session()->hasOldInput()) {
            return true;
        }

        $flashs = ['errors', 'warning', 'notice', 'success'];

        if ($request->session()->has($flashs)) {
            return true;
        }

        return false;
    }

    public function isPassportRequest(Request $request): bool
    {
        return $request->is('oauth/*');
    }
}
