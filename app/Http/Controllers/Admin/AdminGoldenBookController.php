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

class AdminGoldenBookController extends Controller
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
        $this->authorize('viewAny', GoldenBookPost::class);
        $goldenBookPosts = GoldenBookPost::paginate(10);

        return view('admin.goldenbook.index', [
            'goldenBookPosts' => $goldenBookPosts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     *
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $this->authorize('view', GoldenBookPost::class);
        $goldenBookPost = GoldenBookPost::findOrFail($id);
        $this->authorize('view', $goldenBookPost);

        return view('admin.contacts.show', ['contact' => $goldenBookPost]);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->authorize('delete', GoldenBookPost::class);
        $goldenBookPost = GoldenBookPost::findOrFail($id);
        $this->authorize('delete', $goldenBookPost);
        $goldenBookPost->delete();

        return redirect(route('admin.goldenbook.index'))
            ->withSuccess('Goldenbook post successfully deleted');
    }
}
