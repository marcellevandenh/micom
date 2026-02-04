<?php

namespace Surgems\RedirectUrls\Controllers;

use Surgems\RedirectUrls\Facades\Redirect;

class DashboardController
{
    public function index()
    {
        $redirects = Redirect::all();

        return view('redirect-urls::dashboard', ['redirects' => $redirects]);
    }
}
