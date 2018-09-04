<?php

namespace App\Http\Controllers\Front;

use App\Models\Contact;
use App\Models\Picture;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', ['contacts' => $contacts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request);

        $this->validate(request(), [
            'name' => 'string|required|min:3|max:255',
            'email' => 'nullable',
            'message' => 'min:25',
        ]);

        Contact::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
        ]);

//        TODO Show success
        return redirect(route('contact.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return view('contacts.show', ['contact' => $contact]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit', ['contact' => $contact]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //TODO Fix pictures updates
        //TODO title uniques for update ?
        $this->validate(request(), [
            'title' => 'string|required|unique:contacts|min:2|max:255',
            'seo_title' => 'nullable',
            'body' => 'nullable',
            'active' => 'boolean',
            'password' => 'nullable|string',
            'pictures' => 'required',
            Rule::exists('users')->where(function ($query) {
                $query->where('user_id', 1);
            }),
        ]);

        $contact = Contact::find($id);

        $contact->title = $request->input('title');
        $contact->seo_title = $request->input('seo_title', '');
        $contact->body = $request->input('body', '');
        $contact->active = $request->input('active');
        $contact->user_id = Auth::id();
        $contact->password = Hash::make($request->input('password'));

        $contact->save();

        foreach ($request->pictures as $uploaderPicture) {
            $picture = new Picture();
            $picture->filename = Storage::disk('uploads')->put('contacts/' . $contact->id, $uploaderPicture);
            $contact->pictures()->save($picture);
        }


        return redirect(route('contacts.show', ['contact' => $contact]));
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
        // Suppression des fichiers (dossier)
        Storage::disk('uploads')->deleteDirectory('contacts/' . $contact->id);
        $contact->pictures()->delete();

        $contact->delete();

        Session::flash('flash_message', 'Contact successfully deleted');

        return Redirect::back();
    }
}
