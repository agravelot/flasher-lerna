<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin Album */
class AlbumShowResource extends JsonResource
{
    /**
     * @var Request
     */
    private $request;

    /**
     * Transform the resource into an array.
     *
     * @param  Request
     */
    public function toArray($request): array
    {
        $this->request = $request;
        $album = Album::newModelInstance($this);

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'meta_description' => $this->meta_description,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'body' => $this->body,
            'private' => $this->private,
            'medias' => MediaResource::collection($this->getMedia(Album::PICTURES_COLLECTION)),
            'categories' => CategoryResource::collection($this->categories),
            'cosplayers' => CosplayerResource::collection($this->cosplayers),
            //'user' => new UserResource($this->user),
            'links' => [
                'edit' => $this->when(
                    $this->checkCan('update', $album),
                    "/admin/albums/{$this->slug}/edit"
                ),
            ],
        ];
    }

    /**
     * Check if the user has the ability to according to the policy.
     */
    private function checkCan(string $permission, Album $album): bool
    {
        return Auth::guard('api')->check() && Auth::guard('api')->user()->can($permission, $album);
    }
}
