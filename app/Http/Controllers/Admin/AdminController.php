<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class AdminController extends Controller
{
    /**
     * Display dashboard.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function __invoke(): View
    {
        $this->authorize('dashboard');

        return view('admin.admin');
    }
}
