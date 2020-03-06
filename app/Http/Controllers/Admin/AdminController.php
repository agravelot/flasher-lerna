<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Display dashboard.
     *
     * @throws AuthorizationException
     */
    public function __invoke()
    {
        $this->authorize('dashboard');

        return redirect(config('app.admin_url'));
    }
}
