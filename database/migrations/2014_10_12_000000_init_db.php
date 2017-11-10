<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * configs table
         */
        \Schema::create('configs', function (Blueprint $table) {
            $table->bigIncrements('config_id');
            $table->string('config_name', 50)->unique();
            $table->text('config_value');
        });

        /**
         * menus table
         */
        \Schema::create('menus', function (Blueprint $table) {
            $table->increments('menu_id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('title');
            $table->string('url');
            $table->string('type', 10)->index();
            $table->integer('sort')->unsigned()->nullable()->index();
            $table->integer('left')->unsigned()->nullable()->index();
            $table->integer('right')->unsigned()->nullable()->index();
            $table->string('status', 10)->index();

            $table->foreign('parent_id')->references('menu_id')->on('menus')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        \Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('level', 20);
            $table->rememberToken();
            $table->timestamps();
            $table->string('status', 10)->index();
        });

        \Schema::create('user_meta', function (Blueprint $table) {
            $table->increments('meta_id');
            $table->integer('user_id')->unsigned();
            $table->string('meta_key', 50)->index();
            $table->text('meta_value');

            $table->unique(['user_id', 'meta_key']);

            $table->foreign('user_id')->references('user_id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        \Schema::create('services', function(Blueprint $table) {
            $table->increments('service_id');
            $table->string('title');
            $table->text('content');
            $table->string('status', 20)->index();
            $table->timestamps();
        });

        \Schema::create('service_meta', function (Blueprint $table) {
            $table->increments('meta_id');
            $table->integer('service_id')->unsigned();
            $table->string('meta_name', 50)->index();
            $table->text('meta_value');

            $table->unique(['service_id', 'meta_name']);

            $table->foreign('service_id')->references('service_id')->on('services')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        \Schema::create('service_comments', function (Blueprint $table) {
            $table->increments('comment_id');
            $table->integer('service_id')->unsigned();
            $table->string('comment_email', 150);
            $table->string('comment_name', 150);
            $table->string('comment_phone', 20);
            $table->text('comment_content');
            $table->string('status', 20)->index();
            $table->timestamps();

            $table->foreign('service_id')->references('service_id')->on('services')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        \Schema::create('pages', function(Blueprint $table) {
            $table->increments('page_id');
            $table->string('title');
            $table->text('content');
            $table->string('status', 20)->index();
            $table->timestamps();
        });

        \Schema::create('albums', function(Blueprint $table) {
            $table->increments('album_id');
            $table->string('title');
            $table->text('description');
            $table->string('type', 20)->index();
            $table->string('status', 20)->index();
            $table->timestamps();
        });

        \Schema::create('media', function(Blueprint $table) {
            $table->increments('media_id');
            $table->integer('album_id')->unsigned();
            $table->string('title');
            $table->text('description');
            $table->string('file');
            $table->string('status', 20)->index();
            $table->timestamps();

            $table->foreign('album_id')->references('album_id')->on('albums')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        \Schema::create('schedules', function(Blueprint $table) {
            $table->increments('schedule_id');
            $table->string('schedule_name');
            $table->text('schedule_content');
            $table->dateTime('schedule_time');
            $table->string('status', 20)->index();
            $table->timestamps();
        });

        \Schema::create('promotions', function(Blueprint $table) {
            $table->increments('promotion_id');
            $table->string('title');
            $table->text('content');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status', 20)->index();
            $table->timestamps();
        });

        \Schema::create('newsletters', function(Blueprint $table) {
            $table->increments('id');
            $table->string('email', 150);
            $table->string('phone', 20);
            $table->string('name', 150);
            $table->text('content');
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
        \Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign('menus_parent_id_foreign');
        });

        \Schema::table('user_meta', function (Blueprint $table) {
            $table->dropForeign('user_meta_user_id_foreign');
        });

        \Schema::table('service_meta', function (Blueprint $table) {
            $table->dropForeign('service_meta_service_id_foreign');
        });

        \Schema::table('service_comments', function (Blueprint $table) {
            $table->dropForeign('service_comments_service_id_foreign');
        });

        \Schema::table('media', function (Blueprint $table) {
            $table->dropForeign('media_album_id_foreign');
        });

        \Schema::dropIfExists('configs');
        \Schema::dropIfExists('menus');

        \Schema::dropIfExists('users');
        \Schema::dropIfExists('user_meta');
        \Schema::dropIfExists('pages');
        \Schema::dropIfExists('services');
        \Schema::dropIfExists('service_meta');
        \Schema::dropIfExists('service_comments');
        \Schema::dropIfExists('albums');
        \Schema::dropIfExists('media');
        \Schema::dropIfExists('schedules');
        \Schema::dropIfExists('promotions');
        \Schema::dropIfExists('newsletters');
    }
}
