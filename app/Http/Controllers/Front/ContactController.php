<?php

namespace App\Http\Controllers\Front;

use App\Models\Contact;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContactStoreRequest;

class ContactController extends Controller
{
    /**
     * Show the form for creating a new contact.
     */
    public function index(): View
    {
        return view('contacts.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactStoreRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return redirect(route('contact.index'))
            ->withSuccess('Your message has been sent');
    }
}
