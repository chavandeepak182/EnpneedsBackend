<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $table="experiences";
    protected $fillable = [
        'company','location','position','from','to'
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
   
}