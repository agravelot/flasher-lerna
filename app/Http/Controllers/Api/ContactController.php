<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Adapters\Keycloak\GroupQuery;
use App\Facades\Keycloak;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactStoreRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Notifications\ContactSent;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactStoreRequest $request): ContactResource
    {
        $contact = Contact::create($request->validated());

        $query = new GroupQuery();
        $query->search = 'admin';
        $group = Keycloak::groups()->first($query);
        $emails = collect(Keycloak::groups()->members($group->id))->map(static fn ($u) => $u->email)->toArray();
        Notification::route('mail', $emails)->notify(new ContactSent($contact));

        return new ContactResource($contact);
    }
}
