<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['body', 'post_id', 'user_id', 'parent_id'];

    /**
     * One to Many relation
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
