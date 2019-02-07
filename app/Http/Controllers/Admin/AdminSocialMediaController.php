<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMediaRequest;
use App\Models\SocialMedia;

class AdminSocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', SocialMedia::class);
        $socialMedias = SocialMedia::paginate();

        return view('admin.socialmedias.index', compact('socialMedias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', SocialMedia::class);

        return view('admin.socialmedias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SocialMediaRequest $request)
    {
        $this->authorize('view', SocialMedia::class);
        $socialMedia = SocialMedia::create($request->validated());
        $this->authorize('view', $socialMedia);

        return redirect(route('admin.social-medias.index'))
            ->withSuccess('Social media successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $this->authorize('view', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('view', $socialMedia);

        return view('admin.socialmedias.show', compact('socialMedia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $this->authorize('update', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('update', $socialMedia);

        return view('admin.socialmedias.edit', compact('socialMedia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SocialMediaRequest $request, int $id)
    {
        $this->authorize('update', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('update', $socialMedia);
        $socialMedia->update($request->validated());

        return redirect(route('admin.social-medias.index'))
            ->withSuccess('Social media successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->authorize('delete', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('delete', $socialMedia);
        $socialMedia->delete();

        return redirect(route('admin.social-medias.index'))
            ->withSuccess('Social media successfully deleted');
    }
}
