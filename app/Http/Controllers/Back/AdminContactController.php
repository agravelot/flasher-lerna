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
     */
    public function index()
    {
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
     */
    public function show(Contact $contact)
    {
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
        $contact->delete();

        return Redirect::back()->withSuccess('Contact successfully deleted');
    }
}
