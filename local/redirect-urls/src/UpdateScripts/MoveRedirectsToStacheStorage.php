<?php

namespace Surgems\RedirectUrls\UpdateScripts;

use Statamic\Facades\YAML;
use Statamic\UpdateScripts\UpdateScript;
use Surgems\RedirectUrls\Facades\Redirect;

class MoveRedirectsToStacheStorage extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldversion)
    {
        return $this->isUpdatingTo('2.1');
    }

    public function update()
    {
        $import_path = public_path('redirect-urls/database/redirect-urls.yaml');
        $redirects = Yaml::file($import_path)->parse() ?? [];

        if (count($redirects)) {
            foreach ($redirects as $redirect) {
                $from = isset($redirect[0]) ? rtrim(parse_url($redirect[0])['path'], '/') : '/';
                $to = isset($redirect[1]) ? rtrim(parse_url($redirect[1])['path'], '/') : '/';
                $type = isset($redirect[2]) ? intval($redirect[2]) : 301;
                $active = $redirect[3] ?? true;

                if ($from != '/' && ! Redirect::query()->where('from', $from)->first()) {
                    try {
                        $stached_redirect = Redirect::make()
                            ->from($from)
                            ->to($to)
                            ->type($type)
                            ->active($active);
    
                        $stached_redirect->save();
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        $this->console()->info('Redirects moved to stache storage');
    }
}
