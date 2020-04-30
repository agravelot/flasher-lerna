<?php

namespace App\Models;

use App\Abilities\CanResetPassword;
use App\Abilities\MustVerifyEmail;
use App\Traits\ClearsResponseCache;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmailInterface
{
    use MustVerifyEmail, CanResetPassword, Notifiable, Impersonate, HasApiTokens, ClearsResponseCache;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'notify_on_album_published',
    ];

    protected $attributes = [
        'notify_on_album_published' => true,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['name'];

    /**
     * @var array<string>
     */
    protected $dates = [
        'email_verified_at', 'updated_at', 'created_at',
    ];

    public function getNameAttribute(): string
    {
        if ($this->token) {
            return $this->token->preferred_username;
        }

        return $this->attributes['name'];
    }

    /**
     * Return the albums posted by this user.
     */
    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    /**
     * Return the posts posted by this user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Return the linked cosplayer to this user.
     */
    public function cosplayer(): HasOne
    {
        return $this->hasOne(Cosplayer::class);
    }

    /**
     * Return the contact from this user.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Return the testimonial posts by this user.
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Return if this user has the ability to impersonate.
     */
    public function canImpersonate(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Return if this user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || ($this->token && in_array('admin', $this->token->realm_access->roles, true));
    }
}
