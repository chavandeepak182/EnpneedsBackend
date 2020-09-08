<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanybrochureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companybrochure', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('companies_id');
           
            $table->string('upload_file');
            $table->timestamps();
            $table->foreign('companies_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companybrochure');
    }
}
