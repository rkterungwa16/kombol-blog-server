<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    protected $fillable = ['follower_id', 'user_id'];
    public function follows() 
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
