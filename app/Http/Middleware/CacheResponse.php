<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\ResponseCache\Middlewares\CacheResponse as CacheResponseBase;

class CacheResponse extends CacheResponseBase
{
    /**
     * Bypass cache middleware after form submitted or when a defined flashed session data is set.
     *
     * @param  int|null  $lifetimeInSeconds
     * @param  array  $args
     */
    public function handle(Request $request, Closure $next, $lifetimeInSeconds = null, ...$args): Response
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
