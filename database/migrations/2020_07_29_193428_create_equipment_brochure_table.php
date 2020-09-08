<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentBrochureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_brochure', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('equipment_id'); 
            $table->string('upload_file');
            $table->foreign('equipment_id')->references('id')->on('equipment');
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
        Schema::dropIfExists('equipment_brochure');
    }
}
