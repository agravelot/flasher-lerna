<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\ContactStoreRequest;
use App\Models\Contact;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactStoreRequest $request)
    {
        Contact::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
        ]);

        return redirect(route('contact.create'))->withSuccess('Your message has been sent');
    }
}
