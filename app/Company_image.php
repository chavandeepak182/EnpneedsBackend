<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company_image extends Model
{
    protected $table='company_images';
    protected $fillable = [
        'company_id','image'
    
         ];
        }