<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Vizir\KeycloakWebGuard\Models\KeycloakUser;

class User extends KeycloakUser
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name', 'email', 'role', 'notify_on_album_published',
    ];


    //protected $appends = ['name'];

    /**
     * @var array<string>
     */
//    protected $dates = [
//        'email_verified_at', 'updated_at', 'created_at',
//    ];

//    public function getNameAttribute(): string
//    {
//        //if (method_exists($this, 'token')) {
//        //    return $this->token->preferred_username;
//        //}
//
//        return $this->attributes['name'];
//    }


    /**
     * Return if this user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || ($this->token && in_array('admin', $this->token->realm_access->roles, true));
    }
}
