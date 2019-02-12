<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoldenBookPost;

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
        $this->authorize('index', GoldenBookPost::class);
        $goldenBookPosts = GoldenBookPost::paginate(10);

        return view('admin.goldenbook.index', [
            'goldenBookPosts' => $goldenBookPosts,
        ]);
    }

    /**
     * Display the specified resource.
     *
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
