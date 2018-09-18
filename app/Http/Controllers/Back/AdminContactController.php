<?php

namespace App\Http\Controllers\Back;

use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class AdminContactController extends Controller
{

    /**
     * AdminContactController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', Contact::class);
        $contacts = Contact::all();

        return view('admin.contacts.index', [
            'contacts' => $contacts
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Contact $contact
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Contact $contact)
    {
        $this->authorize('show', $contact);
        return view('admin.contacts.show', ['contact' => $contact]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $contact
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('destroy', $contact);
        $contact->delete();
        return Redirect::back()->withSuccess('Contact successfully deleted');
    }
}
