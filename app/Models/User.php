<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use App\Abilities\CanResetPassword;
use App\Abilities\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Lab404\Impersonate\Models\Impersonate;

/**
 * App\Models\User.
 *
 * @property int                                                                                                       $id
 * @property string                                                                                                    $name
 * @property string                                                                                                    $email
 * @property string                                                                                                    $password
 * @property string                                                                                                    $role
 * @property string|null                                                                                               $email_verified_at
 * @property string|null                                                                                               $remember_token
 * @property \Illuminate\Support\Carbon|null                                                                           $created_at
 * @property \Illuminate\Support\Carbon|null                                                                           $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Album[]                                              $albums
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Contact[]                                            $contact
 * @property \App\Models\Cosplayer                                                                                     $cosplayer
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\GoldenBookPost[]                                     $goldenBookPosts
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Post[]                                               $posts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmailInterface
{
    use MustVerifyEmail, CanResetPassword, Notifiable, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function cosplayer()
    {
        return $this->hasOne(Cosplayer::class);
    }

    public function contact()
    {
        return $this->hasMany(Contact::class);
    }

    public function goldenBookPosts()
    {
        return $this->hasMany(GoldenBookPost::class);
    }

    public function canImpersonate()
    {
        return $this->isAdmin();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
