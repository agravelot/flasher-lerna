<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Repositories\ContactRepositoryEloquent;
use App\Repositories\Contracts\ContactRepository;

class AdminContactController extends Controller
{
    /**
     * @var ContactRepositoryEloquent
     */
    protected $repository;

    /**
     * AdminContactController constructor.
     */
    public function __construct(ContactRepository $repository)
    {
        $this->middleware(['auth', 'verified']);
        $this->repository = $repository;
    }

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
        $contacts = $this->repository->paginate(10);

        return view('admin.contacts.index', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $contact = $this->repository->find($id);
        $this->authorize('view', $contact);

        return view('admin.contacts.show', ['contact' => $contact]);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $contact = $this->repository->find($id);
        $this->authorize('delete', $contact);
        $this->repository->delete($contact->id);

        return back()->withSuccess('Contact successfully deleted');
    }
}
