<?php

namespace Surgems\RedirectUrls\Controllers;

use Cache;
use Illuminate\Http\Request;
use Surgems\RedirectUrls\Facades\Redirect;

class RedirectController
{
    public function delete(Request $request)
    {
        Redirect::find($request->id)->delete();

        Cache::flush(); 

        session()->flash('success', 'Redirect deleted successfully.');

        return redirect()->route('statamic.cp.redirect-urls.dashboard');
    }
}
