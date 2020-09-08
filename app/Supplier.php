<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'category_id','subcategory_id','name','admin','upload_file','contact_person','email','alt-email','country_code','mobile','description','company','address','latitude','longitude','image'];

        public function supplier()
        {
            return $this->hasMany(Supplier_image::class);
        }
}
