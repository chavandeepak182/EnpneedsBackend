<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    
    protected $fillable = [
        'headline','industry','description'
    ];
    public function users()
    {
        return $this->belongsToOne(User::class);
    }
    

}