<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactStoreRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Show the form for creating a new contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contacts.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactStoreRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ContactStoreRequest $request)
    {
        Contact::create($request->validated());

        return redirect(route('contact.create'))->withSuccess('Your message has been sent');
    }
}
