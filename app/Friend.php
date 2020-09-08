<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable =  [
        'user_id','request_person_id'
     ];
     public function user()
{
return $this->belongsTo('App\User');
}
}
