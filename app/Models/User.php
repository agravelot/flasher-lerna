<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function canImpersonate()
    {
        // For example
        return $this->isAdmin();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
