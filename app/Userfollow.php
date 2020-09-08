<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userfollow extends Model
{
    protected $fillable = [
        'user_id','request_user_id'
      ];
      public function user()
      {
      return $this->belongsTo('App\User');
      }
}
