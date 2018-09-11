<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\PictureUploadRequest;
use App\Models\Album;
use App\Models\Picture;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AlbumController extends Controller
{
    /**
     * AlbumController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('verified', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()) {
            $albums = Album::with('pictures')
                ->latest()
                ->get();
        } else {
            $albums = Album::with('pictures')
                ->where('active', true)
                ->where('password', null)
                ->get();
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
        $album = Album::find($id);

        $album->title = $request->input('title');
        $album->seo_title = $request->input('seo_title', '');
        $album->body = $request->input('body', '');
        $album->active = $request->input('active', false);
        $album->user_id = Auth::id();
        $album->password = Hash::make($request->input('password'));

        $album->save();

        foreach ($request->pictures as $uploadedPicture) {
            $picture = new Picture();
            $picture->filename = Storage::disk('uploads')->put('albums/' . $album->id, $uploadedPicture);
            $album->pictures()->save($picture);
        }

        return redirect(route('albums.show', ['album' => $album]))->withSuccess('Album successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Album $album
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Album $album)
    {
        // Suppression des fichiers (dossier)
        Storage::disk('uploads')->deleteDirectory('albums/' . $album->id);
        $album->pictures()->delete();

        $album->delete();

        return Redirect::back()->withSuccess('Album successfully deleted');
    }
}
