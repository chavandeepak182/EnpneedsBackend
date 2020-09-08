<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class supplier_brochure extends Model
{
    protected $table='supplier_brochure';
    protected $fillable = [
        'supplier_id','upload_file'
    
         ];
}
