<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'housenumber','street','city','state','country','zipcode','profile_id','user_id',
    ];
    public function users()
    {
        return $this->belongsToOne(User::class);
    }
    public function profiles()
    {
        return $this->belongsToOne(Profile::class);
    }
   
}
