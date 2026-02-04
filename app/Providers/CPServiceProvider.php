<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Statamic\Statamic;
use Statamic\Facades\CP\Nav;

class CPServiceProvider extends ServiceProvider
{

    public function boot()
    {

        Nav::extend(function ($nav) {
            $nav->remove('Tools', 'Addons');
            $nav->remove('Tools', 'Updates');
            $nav->create('Support')
                ->section('Surge')
                ->url(env('STATAMIC_SUPPORT_URL', 'https://surge-online.co.uk/development/support'))
                ->attributes('target=_new')
                ->icon('email-utility'); //https://github.com/statamic/cms/tree/4.x/resources/svg/icons/light
        });

    }
}
