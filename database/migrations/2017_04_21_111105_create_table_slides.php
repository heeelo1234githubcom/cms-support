<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSlides extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        \Schema::create('slides', function (Blueprint $table) {
            $table->increments('slide_id');
            $table->string('title');
            $table->string('path');
            $table->string('status', 20)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('slides');
    }
}
