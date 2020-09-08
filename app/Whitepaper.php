<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Whitepaper extends Model
{
    protected $fillable = ['title',
    'description','company_name','upload_file'
];
}
