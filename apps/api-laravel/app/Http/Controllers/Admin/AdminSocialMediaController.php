<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMediaRequest;
use App\Models\SocialMedia;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminSocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
     *
     * @throws AuthorizationException
     */
    public function store(SocialMediaRequest $request): RedirectResponse
    {
        $this->authorize('view', SocialMedia::class);
        $socialMedia = SocialMedia::create($request->validated());
        $this->authorize('view', $socialMedia);

        return redirect(route('admin.social-medias.index'))
            ->with('success', 'Social media successfully added');
    }

    /**
     * Display the specified resource.
     *
     *
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
     *
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
     *
     * @throws AuthorizationException
     */
    public function update(SocialMediaRequest $request, int $id): RedirectResponse
    {
        $this->authorize('update', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('update', $socialMedia);
        $socialMedia->update($request->validated());

        return redirect(route('admin.social-medias.index'))
            ->with('success', 'Social media successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws Exception
     * @throws AuthorizationException
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', SocialMedia::class);
        $socialMedia = SocialMedia::findOrFail($id);
        $this->authorize('delete', $socialMedia);
        $socialMedia->delete();

        return redirect(route('admin.social-medias.index'))
            ->with('success', 'Social media successfully deleted');
    }
}
