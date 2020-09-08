<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileimgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profileimgs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id'); 
            $table->string('profileimg');
            $table->foreign('profile_id')->references('id')->on('profiles');
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
        Schema::dropIfExists('profileimgs');
    }
}
