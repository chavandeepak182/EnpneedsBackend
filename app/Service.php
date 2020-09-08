<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'category_id','subcategory_id','name','contact_person','email','alt-email','country_code','mobile','description','company','address','latitude','longitude','image','admin','upload_file'];
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
}
