<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['post_id', 'user_id'];
    public function user() 
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function blogPosts()
    {
        return $this->belongsTo('App\Models\Post', 'post_id');
    }
}
