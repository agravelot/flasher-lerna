<?php

declare(strict_types=1);

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
     *
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, ?string $redirectToRoute = null)
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
