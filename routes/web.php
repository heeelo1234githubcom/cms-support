<?php


/*
|--------------------------------------------------------------------------
| Backend ALl Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'manage'], function() {

    /**
     * backend socialite login
     */
    Route::group(['prefix' => 'socialite'], function() {

        Route::get('/{type}/callback', 'Api\SocialController@handleProviderCallback');

        Route::get('/{type}', 'Api\SocialController@redirectToProvider')
            ->name('social_login');
    });

    /**
     * all other routes
     */
    Route::get('/{all?}', function () {

        return view('backend.dashboard');

    })->where('all', '.*');

});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::pattern('page', '\d+');

Route::group(['namespace' => 'Web'], function() {

    Route::get('/', 'HomeController@index')
        ->name('home_page');

    Route::get('/sitemap.xml', 'HomeController@siteMap')
        ->name('site_map');

    Route::get('/sitemap-misc.xml', 'HomeController@siteMapMisc')
        ->name('site_map_misc');

    Route::get('/sitemap-service.xml', 'HomeController@siteMapService')
        ->name('site_map_service');

    Route::get('/sitemap-media.xml', 'HomeController@siteMapMedia')
        ->name('site_map_media');

    Route::get('/sitemap-promotion.xml', 'HomeController@siteMapPromotion')
        ->name('site_map_promotion');

    Route::get('/sitemap-page.xml', 'HomeController@siteMapPage')
        ->name('site_map_page');

    Route::get('/khuyen-mai', 'PromotionController@index')
        ->name('promotion_page');

    Route::get('/lien-he', 'HomeController@contact')
        ->name('contact_page');

    Route::post('/lien-he', 'HomeController@contactSubmit')
        ->name('contact_submit');

    Route::post('/newsletter', 'HomeController@newsletter')
        ->name('newsletter_form');

    Route::get('/sitemap.xml', 'HomeController@siteMap')
        ->name('sitemap_page');

    Route::get('/thu-vien/photo', 'MediaController@photo')
        ->name('photo_page');

    Route::get('/thu-vien/photo/{slug}', 'MediaController@albumDetail')
        ->name('album_detail');

    Route::get('/thu-vien/video', 'MediaController@video')
        ->name('video_page');

    Route::get('/dich-vu/{slug}', 'ServiceController@detail')
        ->name('service_detail');

    Route::get('/khuyen-mai/{slug}', 'PromotionController@detail')
        ->name('promotion_detail');

    Route::get('/{slug}', 'PageController@detail')
        ->name('page_detail');
});