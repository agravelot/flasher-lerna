<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Contact;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Access\AuthorizationException;

class AdminContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     *
     * @return View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Contact::class);
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
     * @throws AuthorizationException
     *
     * @return View
     */
    public function show(int $id): View
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
     * @throws AuthorizationException
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', Contact::class);
        $contact = Contact::findOrFail($id);
        $this->authorize('delete', $contact);
        $contact->delete();

        return redirect(route('admin.contacts.index'))
            ->withSuccess('Contact successfully deleted');
    }
}
