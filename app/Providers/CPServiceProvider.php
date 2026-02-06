<?php

namespace App\Providers;

use Statamic\Statamic;
use Statamic\Facades\CP\Nav;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

class CPServiceProvider extends ServiceProvider
{

    public function boot()
    {

        Nav::extend(function ($nav) {
            // $nav->remove('Tools', 'Addons');
            // $nav->remove('Tools', 'Updates');

            $nav->create('Backend System')
                ->section('Admin')
                ->url('/admin/dashboard')
                ->attributes('target=_admin')
                ->icon('cog');

            $nav->create('Support')
                ->section('Surge')
                ->url(env('STATAMIC_SUPPORT_URL', 'https://surge-online.co.uk/development/support'))
                ->attributes('target=_new')
                ->icon('email-utility'); //https://github.com/statamic/cms/tree/4.x/resources/svg/icons/light

             RedirectIfAuthenticated::redirectUsing(function () {
                return route(backpack_user()->getRedirectRoute());
            });
        });

    }
}
