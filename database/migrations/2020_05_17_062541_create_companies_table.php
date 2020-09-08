<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('image');
            $table->string('name');
            $table->string('website_url');
            $table->text('address');
            $table->string('email');
            $table->string('alt_email');
            $table->string('c_size');
            $table->string('c_type');
            $table->string('founded_date');
            $table->text('company_details');
            $table->string('latitute');
            $table->string('longitute');
            $table->mediumText('upload_file');
            $table->timestamps();
            $table->foreign('user_id')
            ->references('id')
            ->on('users');
  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
