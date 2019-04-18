<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace Modules\Album\Transformers;

use App\Models\Album;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Modules\Category\Transformers\CategoryResource;

class AlbumShowResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        $album = Album::newModelInstance($this);

        return [
            'id' => $album->id,
            'slug' => $album->slug,
            'title' => $album->title,
            'published_at' => $album->published_at,
            'created_at' => $album->created_at,
            'body' => $album->body,
            'private' => $album->private,
            'medias' => MediaResource::collection($album->media),
            'categories' => CategoryResource::collection($album->categories),
            'cosplayers' => CosplayerResource::collection($album->cosplayers),
            'user' => new UserResource($album->user),
            'links' => [
                'download' => $this->when(
                    $this->checkCan('download', $album),
                    route('download-albums.show', ['slug' => $album->slug])
                ),
                'edit' => $this->when(
                    $this->checkCan('update', $album),
                    route('admin.albums.edit', ['slug' => $album->slug])
                ),
            ],
        ];
    }

    /**
     * Check if the user has the ability to according to the policy.
     *
     * @param string $permission
     * @param Album  $album
     *
     * @return bool
     */
    private function checkCan(string $permission, Album $album)
    {
        return Auth::guard('api')->check()
            && Auth::guard('api')->user()->can($permission, $album);
    }
}
