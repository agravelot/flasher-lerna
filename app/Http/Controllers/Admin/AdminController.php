<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

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

        return redirect()->away(config('app.admin_url'));
    }
}
