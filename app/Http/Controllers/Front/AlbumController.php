<?php

namespace App\Http\Controllers\Front;

use App\Models\Album;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{
    /**
     * AlbumController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'string|required|unique:albums|min:6|max:255',
            'seo_title' => 'nullable',
            'body' => 'nullable',
            'active' => 'nullable',
            'user_id' => 'nullable',
            'password' => 'required|string',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            $albums = Album::all();
        } else {
            $albums = Album::where('active', true)->get();
        }
        return view('albums.index', ['albums' => $albums]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('albums.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'title' => 'string|required|unique:albums|min:6|max:255',
            'seo_title' => 'nullable',
            'body' => 'nullable',
            'active' => 'boolean',
            'user_id' => 'nullable',
            'password' => 'nullable|string',
        ]);

        $album = Album::create([
            'title' => $request->input('title'),
            'seo_title' => $request->input('seo_title'),
            'body' => $request->input('body'),
            'active' => $request->input('active'),
            'user_id' => Auth::id(),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect(route('albums.show', ['album' => $album]));
    }

    /**
     * Display the specified resource.
     *
     * @param Album $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        return view('albums.show', ['album' => $album]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Album $album
     * @return void
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Album $album
     * @return void
     */
    public function destroy(Album $album)
    {
        //
    }
}
