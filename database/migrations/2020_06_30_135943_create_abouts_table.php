<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->unsignedBigInteger('user_id');
            
            $table->string('headline');
            $table->string('industry');
            $table->string('description');
            
           
            
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
        Schema::dropIfExists('abouts');
    }
}



