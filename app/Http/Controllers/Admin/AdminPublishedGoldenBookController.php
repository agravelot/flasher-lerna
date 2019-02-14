<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublishedGoldenBookRequest;
use App\Models\GoldenBookPost;

class AdminPublishedGoldenBookController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param PublishedGoldenBookRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PublishedGoldenBookRequest $request)
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
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->authorize('delete', GoldenBookPost::class);
        $goldenbookPost = GoldenBookPost::findOrFail($id);
        $this->authorize('delete', $goldenbookPost);
        $goldenbookPost->unpublish()->save();

        return redirect(route('admin.goldenbook.index'))
            ->withSuccess('Goldenbook post unpublished');
    }
}
