<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cdetails extends Model
{
    protected $fillable = [
        'c_name','joined','c_location'
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
