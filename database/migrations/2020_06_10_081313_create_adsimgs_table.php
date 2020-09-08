<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsimgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adsimgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ads_id');
           
            $table->string('ads_img');
            $table->timestamps();
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adsimgs');
    }
}