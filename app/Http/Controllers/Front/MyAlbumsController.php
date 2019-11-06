<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MyAlbumsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('profile.my-albums');
    }
}
