<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbumRequest;
use App\Http\Requests\PublishedGoldenBookRequest;
use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use App\Models\GoldenBookPost;
use Carbon\Carbon;
use Spatie\MediaLibrary\FileAdder\FileAdder;

class AdminPublishedGoldenBookController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PublishedGoldenBookRequest $request)
    {
//        $this->authorize('delete', GoldenBookPost::class);
        $goldenbookPost = GoldenBookPost::find($request->get('goldenbook_id'));
//        $this->authorize('delete', $goldenbookPost);
        $goldenbookPost->publish();
        return redirect(route('admin.goldenbook.index'))
            ->withSuccess('Goldenbook post published');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
//        $this->authorize('delete', GoldenBookPost::class);
        $goldenbookPost = GoldenBookPost::findOrFail($id);
//        $this->authorize('delete', $goldenbookPost);
        $goldenbookPost->delete();

        return redirect(route('admin.goldenbook.index'))
            ->withSuccess('Goldenbook post unpublished');
    }


}
