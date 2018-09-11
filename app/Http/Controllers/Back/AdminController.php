<?php

namespace App\Http\Controllers\Back;

use App\Models\Album;
use App\Models\Contact;
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

        return view('admin.dashboard', [
            'userCount' => $userCount,
            'albumCount' => $albumCount
        ]);
    }

    /**
     * Display albums
     *
     * @return \Illuminate\Http\Response
     */
    public function albums()
    {
        $albums = Album::with('pictures')->latest()->get();

        return view('admin.album', [
            'albums' => $albums
        ]);
    }


    /**
     * Display contacts
     *
     * @return \Illuminate\Http\Response
     */
    public function contacts()
    {
        $contacts = Contact::all();

        return view('admin.contact', [
            'contacts' => $contacts
        ]);
    }

    /**
     * Display contacts
     *
     * @return \Illuminate\Http\Response
     */
    public function cosplayers()
    {
        $cosplayers = Album::with('pictures')->latest()->get();

        return view('admin.cosplayer', [
            'cosplayers' => $cosplayers
        ]);
    }
}
