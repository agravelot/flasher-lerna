<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Models;

use Eloquent;
use Laravel\Passport\Token;
use Laravel\Passport\Client;
use Illuminate\Support\Carbon;
use App\Abilities\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use App\Abilities\CanResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailInterface;

/**
 * App\Models\User.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Album[] $albums
 * @property Collection|Contact[] $contact
 * @property Cosplayer $cosplayer
 * @property Collection|GoldenBookPost[] $goldenBookPosts
 * @property DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property Collection|Post[] $posts
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @property Collection|Client[] $clients
 * @property Collection|Token[] $tokens
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
