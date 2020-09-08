<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class replydislike extends Model
{
    protected $fillable = [
        'reply_id', 'user_id'
    ];
}
