<?php

namespace App\Http\Controllers\Back;

use App\Http\Requests\PictureUploadRequest;
use App\Models\Album;
use App\Models\Picture;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AdminAlbumController extends Controller
{
    /**
     * AdminAlbumController constructor.
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
        $albums = Album::with('pictures')->latest()->get();

        return view('admin.albums.index', [
            'albums' => $albums
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.albums.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PictureUploadRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PictureUploadRequest $request)
    {
        //TODO Store categories
        $album = Album::create([
            'title' => $request->input('title'),
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

        return redirect(route('admin.albums.show', ['album' => $album]));
    }

    /**
     * Display the specified resource.
     *
     * @param Album $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        return view('admin.albums.show', ['album' => $album]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Album $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        return view('admin.albums.edit', ['album' => $album]);
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
        //TODO Update categories
        $album = Album::with(['pictures', 'categories'])->find($id);

        $album->title = $request->input('title');
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

        return redirect(route('admin.albums.show', ['album' => $album]))->withSuccess('Album successfully updated');
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
