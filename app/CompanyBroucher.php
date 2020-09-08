<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyBroucher extends Model
{
    protected $table="companybrochure";
    protected $fillable = [
        'companies_id','upload_file'
    ];
}
