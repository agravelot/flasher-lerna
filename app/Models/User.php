<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@orma.fr>
 */

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Impersonate;

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
