<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Comment.
 *
 * @property int                                           $id
 * @property \Illuminate\Support\Carbon|null               $created_at
 * @property \Illuminate\Support\Carbon|null               $updated_at
 * @property int                                           $user_id
 * @property int                                           $commentable_id
 * @property string                                        $commentable_type
 * @property string                                        $body
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property \App\Models\User                              $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereUserId($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['body', 'post_id', 'user_id', 'parent_id'];

    /**
     * One to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

//    /**
//     * One to Many relation
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
//     */
//    public function post()
//    {
//        return $this->belongsTo(Post::class);
//    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
