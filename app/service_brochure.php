<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class service_brochure extends Model
{
    protected $table='service_brochure';
    protected $fillable = [
        'service_id','upload_file'
    
         ];
}
