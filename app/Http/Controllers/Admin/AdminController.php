<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class AdminController extends Controller
{
    /**
     * Display dashboard.
     *
     * @throws AuthorizationException
     *
     * @return Response
     */
    public function __invoke()
    {
        $this->authorize('dashboard');

        return view('admin.admin');
    }
}
