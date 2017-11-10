<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        \Schema::create('contacts', function (Blueprint $table) {
            $table->increments('contact_id');
            $table->string('contact_name', 190)->index();
            $table->string('contact_email', 150);
            $table->string('contact_phone', 20);
            $table->text('contact_content');
            $table->string('status', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('contacts');
    }
}
