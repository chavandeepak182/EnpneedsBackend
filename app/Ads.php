<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $fillable = [
       'category_id','subcategory_id', 'description', 'title','name','email','mobileno','address','company_name',
    ];
    
   
}
