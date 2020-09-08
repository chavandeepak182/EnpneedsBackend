<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedInteger('category_id'); 
            $table->unsignedInteger('subcategory_id');
            $table->string('name');
            $table->string('email');
            $table->string('contact_person');
            $table->string('alt_email');
            $table->integer('country_code');
            $table->string('mobile');
            $table->longText('description');
            $table->string('company');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->binary('image');
            $table->string('admin');
            $table->mediumText('upload_file');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}



