<?php

namespace App\Http\Controllers\Back;

use App\Http\Requests\AlbumRequest;
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', Album::class);
        $albums = Album::with('pictures')
            ->latest()
            ->paginate(10);

        return view('admin.albums.index', [
            'albums' => $albums
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Album::class);
        return view('admin.albums.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AlbumRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(AlbumRequest $request)
    {
        //TODO Store categories
        $album = new Album();
        $album->title = $request->input('title');
        $album->body = $request->input('body');
        $album->publish = $request->input('publish', false);
        $album->user_id = Auth::id();
        $album->password = Hash::make($request->input('password'));

        $this->authorize('create', $album);

        $album->save();

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Album $album)
    {
        $this->authorize('view', Album::class);
        return view('admin.albums.show', ['album' => $album]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Album $album
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Album $album)
    {
        $this->authorize('edit', $album);
        return view('admin.albums.edit', ['album' => $album]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AlbumRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(AlbumRequest $request, $id)
    {
        //TODO Update categories
        $album = Album::with(['pictures', 'categories'])->find($id);

        $album->title = $request->input('title');
        $album->body = $request->input('body', '');
        $album->publish = $request->input('publish', false);
        //TODO Keep current ?
        $album->user_id = Auth::id();
        $album->password = Hash::make($request->input('password'));

        $this->authorize('update', $album);

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
        $this->authorize('destroy', $album);

        // Suppression des fichiers (dossier)
        Storage::disk('uploads')->deleteDirectory('albums/' . $album->id);
        $album->pictures()->delete();

        $album->delete();

        return Redirect::back()->withSuccess('Album successfully deleted');
    }
}
