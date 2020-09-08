<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class postvideo extends Model
{
    protected $fillable = [
        'post_id', 'uploadfile'
    ];
    public function posts()
    {                      
        return $this->hasMany(post::class);
    }
}
