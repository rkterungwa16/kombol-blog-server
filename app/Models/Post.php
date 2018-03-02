<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $fillable = ['title', 'content', 'author'];

  public function user() 
  {
    return $this->belongsTo('App\Models\User', 'user_id');
  }

  public function likes()
  {
    return $this->belongsToMany('App\Models\User', 'likes');
  }

  public function comments()
  {
    return $this->belongsToMany('App\Models\User', 'comments');
  }
}