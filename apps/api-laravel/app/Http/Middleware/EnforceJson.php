<?php

namespace App\Http\Middleware;

use Closure;

class EnforceJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->headers->has('Accept') || $request->headers->get('Accept') === "*/*") {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
