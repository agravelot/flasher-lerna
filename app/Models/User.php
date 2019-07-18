<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use App\Abilities\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use App\Abilities\CanResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailInterface;

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
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[]  $tokens
 */
class User extends Authenticatable implements MustVerifyEmailInterface
{
    use MustVerifyEmail, CanResetPassword, Notifiable, Impersonate, HasApiTokens;

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

    protected $dates = [
        'email_verified_at', 'updated_at', 'created_at',
    ];

    /**
     * Hash the password.
     *
     * @param $value Non hashed password
     */
    public function setPasswordAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Return the albums posted by this user.
     *
     * @return HasMany
     */
    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    /**
     * Return the posts posted by this user.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Return the linked cosplayer to this user.
     *
     * @return HasOne
     */
    public function cosplayer(): HasOne
    {
        return $this->hasOne(Cosplayer::class);
    }

    /**
     * Return the contact from this user.
     *
     * @return HasMany
     */
    public function contact(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Return the golden book posts by this user.
     *
     * @return HasMany
     */
    public function goldenBookPosts(): HasMany
    {
        return $this->hasMany(GoldenBookPost::class);
    }

    /**
     * Return if this user has the ability to impersonate.
     *
     * @return bool
     */
    public function canImpersonate(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Return if this user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
