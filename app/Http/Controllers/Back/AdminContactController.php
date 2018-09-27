<?php

namespace App\Http\Controllers\Back;

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
     * @param ContactRepository $repository
     */
    public function __construct(ContactRepository $repository)
    {
        $this->middleware(['auth', 'verified']);
        $this->repository = $repository;
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
        $contacts = $this->repository->paginate(10);

        return view('admin.contacts.index', [
            'contacts' => $contacts
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id)
    {
        $this->authorize('delete', Contact::class);
        $this->repository->delete($id);
        return back()->withSuccess('Contact successfully deleted');
    }
}
