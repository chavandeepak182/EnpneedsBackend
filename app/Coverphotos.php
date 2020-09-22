<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coverphotos extends Model
{
    protected $table="coverphotos";
    protected $fillable = ['user_id',
        'coverphoto'
    ];
    public function users()
    {
        return $this->belongsToOne(User::class);
    }
}
