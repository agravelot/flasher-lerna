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

class AlbumShowResource extends Resource
{
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

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
//        download, edit as metadata
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'body' => $this->body,
            'private' => $this->private,
            'medias' => MediaResource::collection($this->media),
            'categories' => CategoryResource::collection($this->categories),
            'cosplayers' => CosplayerResource::collection($this->cosplayers),
            'user' => new UserResource($this->user),
            'links' => [
                'download' => $this->when(
                    $this->checkCan('download', Album::findOrFail($this->id)),
                    route('download-albums.show', ['slug' => $this->slug])
                ),
                'edit' => $this->when(
                    $this->checkCan('update', Album::findOrFail($this->id)),
                    route('admin.albums.edit', ['slug' => $this->slug])
                ),
            ],
        ];
    }
}
