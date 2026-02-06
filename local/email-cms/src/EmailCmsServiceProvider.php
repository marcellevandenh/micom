<?php

namespace Surge\EmailCms;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EmailCmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->mergeConfigFrom(__DIR__.'/config/emailcms.php', 'emailcms');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load package routes, views, migrations
        //$this->loadBackpackRoutes();
        // $this->registerBackpackRoutes();
        $this->loadRoutesFrom(__DIR__.'/routes/backpack.php');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'emailcms');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Optionally, publish views/config
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/emailcms'),
        ], 'emailcms-views');
    }

    /*protected function loadBackpackRoutes()
    {
        Route::group([
            'prefix' => config('backpack.base.route_prefix', 'admin'),
            'middleware' => array_merge(
                (array) config('backpack.base.web_middleware', 'web'),
                (array) config('backpack.base.middleware_key', 'admin'),
                ['web', backpack_middleware()]
            ),
            // 'namespace' => 'Vendor\EmailCms\Http\Controllers\Admin',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/backpack.php');

        });
    }*/

}
