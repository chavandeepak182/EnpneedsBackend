<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requestdata extends Model
{ 
    protected $table="requests";
    protected $fillable = [
        'name', 'url','contact','alternative_name','alternative_contact','company','country','title',
        'discription','type','location','email'

    ];
}
