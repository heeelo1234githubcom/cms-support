<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->after('title')->unique();
        });

        \Schema::table('albums', function (Blueprint $table) {
            $table->string('slug')->after('title')->unique();
        });

        \Schema::table('promotions', function (Blueprint $table) {
            $table->string('slug')->after('title')->unique();
        });

        \Schema::table('pages', function (Blueprint $table) {
            $table->string('slug')->after('title')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
