<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as EnsureEmailIsVerifiedBase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureEmailIsVerified extends EnsureEmailIsVerifiedBase
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  string|null  $redirectToRoute
     *
     * @return Response|RedirectResponse
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if ($request->wantsJson() && ($request->user()->token && $request->user()->token->email_verified === true)) {
            return $next($request);
        }

        if ($request->user()->email_verified) {
            return $next($request);
        }

        return parent::handle($request, $next, $redirectToRoute);
    }
}
