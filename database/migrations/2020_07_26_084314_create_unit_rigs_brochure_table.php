<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitRigsBrochureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_rigs_brochure', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unit_rigs_id'); 
            $table->string('upload_file');
            $table->foreign('unit_rigs_id')->references('id')->on('unit_rigs');
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
        Schema::dropIfExists('unit_rigs_brochure');
    }
}
