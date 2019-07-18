<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\View\View;
use App\Models\SocialMedia;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SocialMediaRequest;
use Illuminate\Auth\Access\AuthorizationException;

class AdminSocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', SocialMedia::class);
        $socialMedias = SocialMedia::paginate();

        return view('admin.socialmedias.index', compact('socialMedias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', SocialMedia::class);

        return view('admin.socialmedias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SocialMediaRequest  $request
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(SocialMediaRequest $request): RedirectResponse
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
     * @param  int  $id
     *
     * @return View
     * @throws AuthorizationException
     */
    public function show(int $id): View
    {
        $this->authorize('view', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('view', $socialMedia);

        return view('admin.socialmedias.show', compact('socialMedia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return View
     * @throws AuthorizationException
     */
    public function edit(int $id): View
    {
        $this->authorize('update', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('update', $socialMedia);

        return view('admin.socialmedias.edit', compact('socialMedia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SocialMediaRequest  $request
     * @param  int  $id
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(SocialMediaRequest $request, int $id): RedirectResponse
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
     * @param  int  $id
     *
     * @return RedirectResponse
     * @throws Exception
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('delete', $socialMedia);
        $socialMedia->delete();

        return redirect(route('admin.social-medias.index'))
            ->withSuccess('Social media successfully deleted');
    }
}
