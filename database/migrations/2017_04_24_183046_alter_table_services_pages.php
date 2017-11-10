<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableServicesPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('services', function (Blueprint $table) {
            $table->string('show_at_home', 10)->after('content')->index();
            $table->integer('parent_id')->after('service_id')->unsigned()->nullable();

            $table->foreign('parent_id')->references('service_id')->on('services')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('services', function (Blueprint $table) {
            $table->dropForeign('services_parent_id_foreign');
        });
    }
}
