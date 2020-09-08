<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table="equipment";
    protected $fillable = [
      'category_id','subcategory_id','name','contact_person','email','alt-email','country_code','mobile','description','company','address','admin','upload_file','latitude','longitude','image'
    ];
    public function category()
    {
      return $this->belongsTo('App\Category');
     }
    public function subcategory()
     {
       return $this->belongsTo('App\Subcategory');
     }

    public function user() {
        return $this->belongsTo('App\User');
     }
     public function equipment()
  {
      return $this->hasMany(Equipment_image::class);
  }
}
