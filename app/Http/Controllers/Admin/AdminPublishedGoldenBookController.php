<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Models\GoldenBookPost;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PublishedGoldenBookRequest;
use Illuminate\Auth\Access\AuthorizationException;

class AdminPublishedGoldenBookController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param PublishedGoldenBookRequest $request
     *
     * @throws AuthorizationException
     *
     * @return RedirectResponse
     */
    public function store(PublishedGoldenBookRequest $request): RedirectResponse
    {
        $this->authorize('create', GoldenBookPost::class);
        $goldenbookPost = GoldenBookPost::find($request->get('goldenbook_id'));
        $this->authorize('create', $goldenbookPost);
        $goldenbookPost->publish()->save();

        return redirect(route('admin.goldenbook.index'))
            ->withSuccess('Goldenbook post published');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', GoldenBookPost::class);
        $goldenbookPost = GoldenBookPost::findOrFail($id);
        $this->authorize('delete', $goldenbookPost);
        $goldenbookPost->unpublish()->save();

        return redirect(route('admin.goldenbook.index'))
            ->withSuccess('Goldenbook post unpublished');
    }
}
