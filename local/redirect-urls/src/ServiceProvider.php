<?php

namespace Surgems\RedirectUrls;

use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Stache\Stache;
use Statamic\Statamic;
use Surgems\RedirectUrls\Contracts\RedirectRepository as RepositoryContract;
use Surgems\RedirectUrls\Http\Middleware\HandleRedirects;
use Surgems\RedirectUrls\Stache\Repositories\RedirectRepository;
use Surgems\RedirectUrls\Stache\Stores\RedirectStore;
use Surgems\RedirectUrls\UpdateScripts\MoveRedirectsToStacheStorage;

class ServiceProvider extends AddonServiceProvider
{
    protected $updateScripts = [
        MoveRedirectsToStacheStorage::class,
    ];

    protected $middlewareGroups = [
        'statamic.web' => [
            HandleRedirects::class,
        ],
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    public function register()
    {
        Statamic::repository(RepositoryContract::class, RedirectRepository::class);
    }

    public function bootAddon()
    {
        $this->bootAddonConfig()
            ->bootAddonNav()
            ->bootAddonViews()
            ->bootStores();
    }

    protected function bootAddonConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'statamic.redirect-urls');

        return $this;
    }

    protected function bootAddonNav()
    {
        Nav::extend(function ($nav) {
            $nav->tools('Redirect Urls')
                ->route('redirect-urls.dashboard')
                ->icon('git')
                ->children([
                    'Import Redirects' => cp_route('redirect-urls.import'),
                ]);
        });

        return $this;
    }

    protected function bootAddonViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'redirect-urls');

        return $this;
    }

    protected function bootStores()
    {
        $store = new RedirectStore();
        $store->directory(base_path('content/redirect-urls'));
        app(Stache::class)->registerStore($store);

        return $this;
    }
}
