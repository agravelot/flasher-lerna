<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Http\Controllers\Controller;

class AdminContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Contact::class);
        $contacts = Contact::paginate(10);

        return view('admin.contacts.index', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $this->authorize('view', Contact::class);
        $contact = Contact::findOrFail($id);
        $this->authorize('view', $contact);

        return view('admin.contacts.show', ['contact' => $contact]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->authorize('delete', Contact::class);
        $contact = Contact::findOrFail($id);
        $this->authorize('delete', $contact);
        $contact->delete();

        return redirect(route('admin.contacts.index'))
            ->withSuccess('Contact successfully deleted');
    }
}
