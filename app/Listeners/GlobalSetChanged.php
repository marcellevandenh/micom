<?php

namespace App\Listeners;

use Statamic\Events\GlobalVariablesSaving;
use Statamic\Events\GlobalSetSaved;
use Illuminate\Support\Facades\Cache;

class GlobalSetChanged
{
    /**
     * Handle the event.
     *
     * @param  \Statamic\Events\GlobalVariablesSaving  $event
     * @return void
     */
    public function handle(GlobalVariablesSaving $event)
    {
        if( $event->variables->robots_contents ) {
            $content = str_replace("robots_contents: |-\n  ", "", $event->variables->robots_contents);
            file_put_contents('./robots.txt', $content);
            \Log::info('We\'ve updated the robots.txt content! - ' . strval($content));
        } elseif( $event->variables->llms_content ) {
            $content = str_replace("llms_content: |-\n  ", "", $event->variables->llms_content);
            file_put_contents('./llms.txt', $content);
            \Log::info('We\'ve updated the llms.txt content! - ' . strval($content));
        }
    }
}