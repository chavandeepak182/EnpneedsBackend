<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class commentdislike extends Model
{
    protected $fillable = [
        'comment_id', 'user_id'
    ];
}
