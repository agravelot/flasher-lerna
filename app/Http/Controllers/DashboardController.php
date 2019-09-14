<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Auth\Access\AuthorizationException;

class DashboardController extends Controller
{
    /**
     * @return Factory|View
     *
     * @throws AuthorizationException
     */
    public function __invoke()
    {
        $this->authorize('dashboard');

        return view('admin.admin');
    }
}
