<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            
                $table->increments('id');
                $table->unsignedBigInteger('user_id');
                $table->string('housenumber');
                $table->text('street');
                $table->string('city');
                $table->string('state');
                $table->string('country');
                $table->text('zipcode');
                
                
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
        Schema::dropIfExists('addresses');
    }
}
