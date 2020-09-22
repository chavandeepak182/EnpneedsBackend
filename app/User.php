<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use  HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','dob','gender','email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
   

 public function ads()
    {
        return $this->hasMany(Ads::class);

    }
    public function posts()
    {
        return $this->hasMany(post::class);
    }
    public function comments() 
    {
        return $this->hasMany(comment::class);
    }
    public function replies() 
    {
        return $this->hasMany(reply::class);
    }
    
  

    public function requests()
    {
        return $this->hasMany(Requestdata::class);
    }
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
        public function supplier()
    {
        return $this->hasMany(Supplier::class);
    }
    
    public function unit_rigs()
    {
        return $this->hasMany(Unit_rigs::class);
    }
    public function service()
    {
        return $this->hasMany('App\Service');
    }
    public function profileimg()
    {
        return $this->hasOne( 'App\Profileimgs', 'id', 'id');
    }
    public function profileimgs()
    {
        return $this->hasOne( Profileimgs::class);
    }
    public function profiles()
    {
        return $this->hasOne( 'App\Profile', 'id', 'user_id');
    }
    
    public function profile()
    {
        return $this->hasOne( Profile::class);
    }
  
    public function addresses()
    {
        return $this->hasOne(Address::class);
    }
    public function abouts()
    {
        return $this->hasOne(About::class);
    }
    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }
    public function education()
    {
        return $this->hasMany(Education::class);
    }
    public function cdetails()
    {
        return $this->hasOne(Cdetails::class);
    }
  
    public function coverphotos()
    {
        return $this->hasOne(Coverphotos::class);
    }
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
    public function follows()
    {
        return $this->hasMany(Follow::class);
    }
    public function whitepapers()
    {
        return $this->hasMany(Whitepaper::class);
    }
    public function friend()
{
return $this->hasMany('App\Friend');
}
public function userfollows()
{
    return $this->hasMany(Userfollow::class);
}

    }



