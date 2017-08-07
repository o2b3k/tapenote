<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->string('big_image_path')->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('width');
            $table->integer('height');
            $table->integer('monument_id')->unsigned();
            $table->timestamps();

            $table->foreign('monument_id')->references('id')->on('monuments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
