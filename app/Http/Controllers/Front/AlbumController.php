<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\PictureUploadRequest;
use App\Models\Album;
use App\Models\Picture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{
    /**
     * AlbumController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
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
     * @param PictureUploadRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PictureUploadRequest $request)
    {
        $this->validate(request(), [
            'title' => 'string|required|unique:albums|min:6|max:255',
            'seo_title' => 'nullable',
            'body' => 'nullable',
            'active' => 'boolean',
            'user_id' => 'nullable',
            'password' => 'nullable|string',
            'pictures' => 'required',
        ]);

        $album = Album::create([
            'title' => $request->input('title'),
            'seo_title' => $request->input('seo_title'),
            'body' => $request->input('body'),
            'active' => $request->input('active'),
            'user_id' => Auth::id(),
            'password' => Hash::make($request->input('password')),
        ]);

        foreach ($request->pictures as $uploaderPicture) {
            $picture = new Picture();
            $picture->filename = Storage::disk('uploads')->put('albums/' . $album->id, $uploaderPicture);
            $album->pictures()->save($picture);
        }

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
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        return view('albums.edit', ['album' => $album]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PictureUploadRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(PictureUploadRequest $request, $id)
    {
        //TODO Fix pictures updates
        //TODO title uniques for update ?
        $this->validate(request(), [
            'title' => 'string|required|unique:albums|min:6|max:255',
            'seo_title' => 'nullable',
            'body' => 'nullable',
            'active' => 'boolean',
            'user_id' => 'nullable',
            'password' => 'nullable|string',
            'pictures' => 'required',
        ]);


        $album = Album::find($id);


        $album->title = $request->input('title');
        $album->seo_title = $request->input('seo_title');
        $album->body = $request->input('body');
        $album->active = $request->input('active');
        $album->user_id = Auth::id();
        $album->password = Hash::make($request->input('password'));

        $album->save();

        foreach ($request->pictures as $uploaderPicture) {
            $picture = new Picture();
            $picture->filename = Storage::disk('uploads')->put('albums/' . $album->id, $uploaderPicture);
            $album->pictures()->save($picture);
        }

        return redirect(route('albums.show', ['album' => $album]));
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
