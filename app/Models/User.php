<?php

namespace App\Models;

use App\Abilities\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use App\Abilities\CanResetPassword;
use App\Traits\ClearsResponseCache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailInterface;

class User extends Authenticatable implements MustVerifyEmailInterface
{
    use MustVerifyEmail, CanResetPassword, Notifiable, Impersonate, HasApiTokens, ClearsResponseCache;

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
    public function contact(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Return the golden book posts by this user.
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
        return $this->role === 'admin';
    }
}
