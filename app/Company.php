<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name','website_url','latitute','longitute','upload_file','address','email','alt_email','c_size','c_type','founded_date','company_details','image'
    ];
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
