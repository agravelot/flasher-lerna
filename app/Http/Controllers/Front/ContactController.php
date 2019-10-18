<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use App\Models\Contact;
use Illuminate\View\View;
use App\Notifications\ContactSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContactStoreRequest;
use Illuminate\Support\Facades\Notification;

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
        $contact = Contact::create($request->validated());

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new ContactSent($contact));

        return redirect(route('contact.index'))
            ->with('success', __('Your message has been sent'));
    }
}
