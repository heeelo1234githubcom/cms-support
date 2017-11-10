<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSchedules extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        \Schema::table('schedules', function (Blueprint $table) {
            $table->string('schedule_email', 150)->after('schedule_name');
            $table->string('schedule_phone', 20)->after('schedule_email');
            $table->index('schedule_name');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {}
}
