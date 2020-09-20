<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    protected $fillable = ['user_id', 'description','file_type','likecount','dislikecount'];

public function comments()
    {                      
        return $this->hasMany(comment::class);
    }
    public function users()
    {
        return $this->hasOne( 'App\User', 'id', 'user_id')->select('id','first_name','last_name');
    }
    public function profileimgs()
    {
        return $this->hasOne( 'App\Profileimgs', 'id', 'user_id');
    }
    public function postimages()
    {
        return $this->hasMany(postimages::class);
    }
    public function postvideos()
    {
        return $this->hasMany(postvideo::class);
    }
    public function replies()
    {                      
        return $this->hasMany(reply::class);
    }

}
