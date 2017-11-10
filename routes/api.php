<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function() {

    return 'Dental Application API';

})->name('application_api');

Route::group(['namespace' => 'Api'], function()
{
    Route::post('/auth/login', 'AuthController@authenticate')
        ->name('backend_login');

    Route::post('/auth/refresh', 'AuthController@refreshToken');

    Route::post('/auth/register', 'AuthController@register');

    Route::group(['middleware' => ['jwt.auth']], function () {

        Route::post('/auth/logout', 'AuthController@logout');

        Route::post('/auth/user', 'AuthController@me');

        Route::post('/setting', 'SettingController@update')
            ->name('update_setting');

        Route::post('/get-setting', 'SettingController@get')
            ->name('get_setting');

        /**
         * user routes
         */
        Route::group(['prefix' => 'user'], function () {

            Route::post('/profile', 'UserController@updateProfile')
                ->name('update_profile');
        });

        /**
         * service routes
         */
        Route::group(['prefix' => 'service'], function () {

            Route::post('/list', 'ServiceController@listServices')
                ->name('list_services');

            Route::post('/save', 'ServiceController@save')
                ->name('save_service');

            Route::post('/get-service', 'ServiceController@get')
                ->name('get_service');

            Route::post('/comments', 'ServiceController@getComments')
                ->name('list_comments');

            Route::post('/comment-status/{id}', 'ServiceController@changeCommentStatus')
                ->name('change_comment_status');

            Route::post('/remove/{id}', 'ServiceController@remove')
                ->name('remove_service');
        });

        /**
         * page routes
         */
        Route::group(['prefix' => 'page'], function () {

            Route::post('/list', 'PageController@listPages')
                ->name('list_pages');

            Route::post('/save', 'PageController@save')
                ->name('save_page');

            Route::post('/get-page', 'PageController@get')
                ->name('get_page');

            Route::post('/change-status/{id}', 'PageController@changeStatus')
                ->name('change_page_status');
        });

        /**
         * schedule routes
         */
        Route::group(['prefix' => 'schedule'], function () {

            Route::post('/list', 'ScheduleController@listSchedules')
                ->name('list_schedules');

            Route::post('/get-schedule/{id}', 'ScheduleController@get')
                ->name('get_schedule');
        });

        /**
         * contact routes
         */
        Route::group(['prefix' => 'contact'], function () {

            Route::post('/list', 'ContactController@listContacts')
                ->name('list_contacts');

            Route::post('/get-contact/{id}', 'ContactController@get')
                ->name('get_contact');
        });

        /**
         * user routes
         */
        Route::group(['prefix' => 'user'], function () {

            Route::post('/list', 'UserController@listUsers')
                ->name('list_users');

            Route::post('/save', 'UserController@save')
                ->name('save_user');

            Route::post('/get-user/{id}', 'UserController@get')
                ->name('get_user');

            Route::post('/change-user-status/{id}', 'UserController@changeStatus')
                ->name('change_user_status');
        });

        /**
         * album routes
         */
        Route::group(['prefix' => 'album'], function () {

            Route::post('/list', 'AlbumController@listAlbums')
                ->name('list_albums');

            Route::post('/save', 'AlbumController@save')
                ->name('save_album');

            Route::post('/get-album/{id}', 'AlbumController@get')
                ->name('get_album');

            Route::post('/change-album-status/{id}', 'AlbumController@changeStatus')
                ->name('change_album_status');
        });

        /**
         * media routes
         */
        Route::group(['prefix' => 'media'], function () {

            /**
             * slide
             */
            Route::post('/list-slide', 'SlideController@listSlides')
                ->name('list_slides');

            Route::post('/save-slide', 'SlideController@save')
                ->name('save_slide');

            Route::post('/get-slide/{id}', 'SlideController@get')
                ->name('get_slide');

            Route::post('/change-slide-status/{id}', 'SlideController@changeStatus')
                ->name('change_slide_status');

            /**
             * media
             */
            Route::post('/list-{type}', 'MediaController@listMedia')
                ->name('list_media');

            Route::post('/save-photo', 'MediaController@savePhoto')
                ->name('save_photo');

            Route::post('/save-video', 'MediaController@saveVideo')
                ->name('save_video');

            Route::post('/get-media/{id}', 'MediaController@get')
                ->name('get_media');

            Route::post('/change-media-status/{id}', 'MediaController@changeStatus')
                ->name('change_media_status');
        });

        /**
         * promotion routes
         */
        Route::group(['prefix' => 'promotion'], function () {

            Route::post('/list', 'PromotionController@listPromotions')
                ->name('list_promotions');

            Route::post('/users', 'PromotionController@listPromotionUsers')
                ->name('list_promotion_users');

            Route::post('/save', 'PromotionController@save')
                ->name('save_promotion');

            Route::post('/get-promotion/{id}', 'PromotionController@get')
                ->name('get_promotion');

            Route::post('/change-promotion-status/{id}', 'PromotionController@changeStatus')
                ->name('change_promotion_status');
        });

        /**
         * menu routes
         */
        Route::group(['prefix' => 'menu'], function () {

            Route::post('/list', 'MenuController@listMenu')
                ->name('list_menu');

            Route::post('/list-parent', 'MenuController@parent')
                ->name('get_parent_menu');

            Route::post('/save', 'MenuController@save')
                ->name('save_menu');

            Route::post('/search', 'MenuController@search')
                ->name('search_menu');

            Route::post('/get-menu/{id}', 'MenuController@get')
                ->name('get_menu');

            Route::post('/remove/{id}', 'MenuController@remove')
                ->name('remove_menu');
        });
    });
});

/**
 * options request
 */
Route::options('{any?}', function () {

    return response(['status' => 'success']);

})->where('any', '.*');
