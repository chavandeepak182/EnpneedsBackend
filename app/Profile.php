<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id',
       'first_name','last_name','email', 'mobile_no','address','postal_code','designation','birth_date',
    ];
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function users()
    {
        return $this->belongsToOne(User::class);
    }
    public function abouts()
    {
        return $this->hasOne(About::class);
    }
    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }
    public function educations()
    {
        return $this->hasOne(Education::class);
    }
    public function cdetails()
    {
        return $this->hasOne(Cdetails::class);
    }
    public function profileimgs()
    {
        return $this->hasOne( 'App\Profileimgs');
    }
    

}






