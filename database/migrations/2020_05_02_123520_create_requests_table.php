<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->string('name');
            $table->string('url');
            $table->integer('contact');
            $table->string('alternative_name');
            $table->integer('alternative_contact');
            $table->string('company');
            $table->string('country');
            $table->string('title');
            $table->string('discription');
            $table->string('type');
            $table->string('location');
            $table->string('email')->unique();
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
        Schema::dropIfExists('requests');
    }
}
