<?php

namespace App\Http\Controllers\Front;

use App\Facades\Keycloak;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactStoreRequest;
use App\Models\Contact;
use App\Notifications\ContactSent;
use App\Services\Keycloak\GroupQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

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

        $query = new GroupQuery();
        $query->search = 'admin';
        $group = Keycloak::groups()->first($query);
        $admins = Keycloak::groups()->members($group->id);
        Notification::send($admins, new ContactSent($contact));

        return redirect(route('contact.index'))
            ->with('success', __('Your message has been sent'));
    }
}
