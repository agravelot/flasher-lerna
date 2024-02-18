<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Adapters\Keycloak\GroupQuery;
use App\Facades\Keycloak;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactStoreApiRequest;
use App\Http\Resources\ContactResource;
use App\Mail\ContactSent;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactStoreApiRequest $request): ContactResource
    {
        $contact = Contact::create($request->validated());

        $query = new GroupQuery();
        $query->search = 'admin';
        $group = Keycloak::groups()->first($query);
        $emails = collect(Keycloak::groups()->members($group->id))->map(static fn ($u) => $u->email)->toArray();
        Mail::to($emails)->send(new ContactSent($contact));

        return new ContactResource($contact);
    }
}
