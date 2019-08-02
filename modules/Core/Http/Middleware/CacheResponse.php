<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\Middlewares\CacheResponse as CacheResponseBase;

class CacheResponse extends CacheResponseBase
{
    /**
     * Bypass cache middleware after form submitted or when a defined flashed session data is set.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  int|null  $lifetimeInSeconds
     * @param  array  $args
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next, $lifetimeInSeconds = null, ...$args ): Response
    {
        if ($request->session()->hasOldInput()) {
            return $next($request);
        }

        $flashs = ['errors', 'warning', 'notice', 'success'];

        foreach ($flashs as $flash) {
            if ($request->session()->has($flash)) {
                return $next($request);
            }
        }

        return parent::handle($request, $next, $lifetimeInSeconds);
    }
}
