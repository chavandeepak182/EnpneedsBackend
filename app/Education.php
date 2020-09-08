<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    
    protected $fillable = [
        'school','degree','field_of_study','start_year','end_year','activities_and_societies',
    ];
   
  
}