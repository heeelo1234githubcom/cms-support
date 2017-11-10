<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Config;
use App\Models\Media;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Promotion;
use App\Models\PromotionUser;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceComment;
use App\Models\ServiceMeta;
use App\Models\User;
use App\Models\UserMeta;
use App\Observers\ModelObserver;
use App\Repositories\AlbumRepository;
use App\Repositories\ConfigRepository;
use App\Repositories\ContactRepository;
use App\Repositories\MediaRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PageRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\PromotionUserRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\ServiceCommentRepository;
use App\Repositories\ServiceMetaRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\SlideRepository;
use App\Repositories\UserMetaRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Barryvdh\Debugbar\ServiceProvider as DebugServiceProvider;
use Illuminate\Foundation\Application;

/**
 * Class AppServiceProvider
 * @package App\Providers
 * @property Application $app
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        /**
         * fix mysql: Specified key was too long error
         */
        \Schema::defaultStringLength(191);

        /**
         * check development mode
         */
        if (isDevelopment()) {

            /**
             * ide helper
             */
            $this->app->register(IdeHelperServiceProvider::class);

            /**
             * debug bar
             */
            $this->app->register(DebugServiceProvider::class);
        }

        Album::observe(ModelObserver::class);
        Config::observe(ModelObserver::class);
        Media::observe(ModelObserver::class);
        Menu::observe(ModelObserver::class);
        Page::observe(ModelObserver::class);
        Promotion::observe(ModelObserver::class);
        PromotionUser::observe(ModelObserver::class);
        Schedule::observe(ModelObserver::class);
        Service::observe(ModelObserver::class);
        ServiceComment::observe(ModelObserver::class);
        ServiceMeta::observe(ModelObserver::class);
        User::observe(ModelObserver::class);
        UserMeta::observe(ModelObserver::class);

        /* init configs */
        if ( !\App::runningInConsole()) {
            app('appConfig')->init();
        }
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        /**
         * register singleton
         */
        $this->app->singleton('album', function($app) {
            return new AlbumRepository($app->cache->getStore());
        });

        $this->app->singleton('appConfig', function($app) {
            return new ConfigRepository($app->cache->getStore());
        });

        $this->app->singleton('media', function($app) {
            return new MediaRepository($app->cache->getStore());
        });

        $this->app->singleton('slide', function($app) {
            return new SlideRepository($app->cache->getStore());
        });

        $this->app->singleton('menu', function($app) {
            return new MenuRepository($app->cache->getStore());
        });

        $this->app->singleton('page', function($app) {
            return new PageRepository($app->cache->getStore());
        });

        $this->app->singleton('promotion', function($app) {
            return new PromotionRepository($app->cache->getStore());
        });

        $this->app->singleton('promotionUser', function($app) {
            return new PromotionUserRepository($app->cache->getStore());
        });

        $this->app->singleton('schedule', function($app) {
            return new ScheduleRepository($app->cache->getStore());
        });

        $this->app->singleton('contact', function($app) {
            return new ContactRepository($app->cache->getStore());
        });

        $this->app->singleton('service', function($app) {
            return new ServiceRepository($app->cache->getStore());
        });

        $this->app->singleton('serviceComment', function($app) {
            return new ServiceCommentRepository($app->cache->getStore());
        });

        /*$this->app->singleton('serviceMeta', function($app) {
            return new ServiceMetaRepository($app->cache->getStore());
        });*/

        $this->app->singleton('user', function($app) {
            return new UserRepository($app->cache->getStore());
        });

        /*$this->app->singleton('userMeta', function($app) {
            return new UserMetaRepository($app->cache->getStore());
        });*/
    }
}
