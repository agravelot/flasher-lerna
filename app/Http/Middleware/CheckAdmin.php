<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->wantsJson() && in_array('admin', $request->user()->token->realm_access->roles, true)) {
            return $next($request);
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'User must have admin privileges to perform this action.');
        }

        return $next($request);
    }
}
