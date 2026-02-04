<?php

namespace Surgems\RedirectUrls\Facades;

use Illuminate\Support\Facades\Facade;
use Surgems\RedirectUrls\Contracts\RedirectRepository;

class Redirect extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RedirectRepository::class;
    }
}
