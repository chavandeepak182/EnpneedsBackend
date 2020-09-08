<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education', function (Blueprint $table) {
            $table->increments('id');
        
            $table->unsignedBigInteger('user_id');
            $table->string('school');
            $table->text('degree');
            $table->string('field_of_study');
            $table->string('start_year');
            $table->string('end_year');
            $table->string('activities_and_societies');
            
           
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
        Schema::dropIfExists('education');
    }
}

