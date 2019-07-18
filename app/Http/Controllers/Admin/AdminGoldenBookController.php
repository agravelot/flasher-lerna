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
use App\Models\GoldenBookPost;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Access\AuthorizationException;

class AdminGoldenBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
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
     * @param  int  $id
     *
     * @return View
     * @throws AuthorizationException
     */
    public function show(int $id): View
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
     * @param  int  $id
     *
     * @return RedirectResponse
     * @throws Exception
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete', GoldenBookPost::class);
        $goldenBookPost = GoldenBookPost::findOrFail($id);
        $this->authorize('delete', $goldenBookPost);
        $goldenBookPost->delete();

        return redirect(route('admin.goldenbook.index'))
            ->withSuccess('Goldenbook post successfully deleted');
    }
}
