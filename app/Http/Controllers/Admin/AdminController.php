<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class AdminController extends Controller
{
    /**
     * Display dashboard.
     *
     * @throws AuthorizationException
     */
    public function __invoke(): View
    {
        $this->authorize('dashboard');

        return view('admin.admin');
    }
}
