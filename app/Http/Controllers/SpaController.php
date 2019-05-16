<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Auth\Access\AuthorizationException;

class SpaController extends Controller
{
    /**
     * @throws AuthorizationException
     *
     * @return Factory|View
     */
    public function index()
    {
        $this->authorize('dashboard');

        return view('admin.admin');
    }
}
