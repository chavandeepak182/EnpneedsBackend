<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class equipment_brochure extends Model
{
    protected $table='equipment_brochure';
    protected $fillable = [
        'equipment_id','upload_file'
    
         ];
}
