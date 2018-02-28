<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function generateToken()
    {
        $this->remember_token = str_random(60);
        $this->save();
        return $this->remember_token;
    }

    public function blogPosts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function likes()
    {
        return $this->belongsToMany('App\Models\Post', 'likes', 'user_id', 'post_id');
    }

    public function comments()
    {
        return $this->belongsToMany('App\Models\Post', 'comments', 'user_id', 'post_id');
    }
}
