<?php

namespace Surgems\RedirectUrls\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Statamic\Support\Str;
use Surgems\RedirectUrls\Facades\Redirect;

class HandleRedirects
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->status() !== 404 || ! config('statamic.redirect-urls.enable', true)) {
            return $response;
        }

        $url = Str::start($request->getRequestUri(), '/');
        $url = Str::substr(Str::finish($url, '/'), 0, -1);

        if (! $redirect = Redirect::findByUrl($url)) {
            return $response;
        }

        try {
            return redirect($redirect->to(), $redirect->type());
        } catch (\Exception $e) {
            return $response;
        }
    }
}
