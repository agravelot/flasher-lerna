<?php

namespace App\Http\Controllers\Back;

use App\Models\Album;
use App\Models\Contact;
use App\Models\Cosplayer;
use App\Models\User;
use App\Http\Controllers\Controller;

class AdminController extends Controller


{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $userCount = User::count();
        $albumCount = Album::count();
        $cosplayerCount = Cosplayer::count();
        $contactCount = Contact::count();

        return view('admin.dashboard.dashboard', [
            'userCount' => $userCount,
            'albumCount' => $albumCount,
            'cosplayerCount' => $cosplayerCount,
            'contactCount' => $contactCount
        ]);
    }
}
