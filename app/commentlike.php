<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class commentlike extends Model
{
    protected $fillable = [
        'comment_id', 'user_id'
    ];
}
