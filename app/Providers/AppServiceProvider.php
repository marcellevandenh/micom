<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\Admin\UserCrudController;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Http\Controllers\Admin\UserCrudController::class
        );

        Event::listen(Login::class, function ($event) {
            $user = Auth::user();

            if (backpack_auth()->check() && $user->user_type === 'ADMIN') {

                session()->put('url.intended', '/cp');
        }
        });

        RedirectIfAuthenticated::redirectUsing(function () {
            return route(backpack_user()->getRedirectRoute());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
