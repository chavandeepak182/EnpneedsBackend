<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    protected $fillable = ['user_id','post_id','comment','commentlikecount','commentdislikecount'];
    public function users()
    {
        return $this->hasOne( 'App\User', 'id', 'user_id')->select('id','first_name','last_name');
    }
    public function posts()
    {
        return $this->belongsToMany(post::class);
    }
    public function replies()
    {
        return $this->hasMany(reply::class);
    }
    public function profileimgs()
    {
        return $this->hasOne( 'App\Profileimgs', 'id', 'user_id');
    }
    
}
