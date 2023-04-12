<?php

namespace CleaniqueCoders\NadiLaravel;

use Illuminate\Support\ServiceProvider;

class NadiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if(! config('nadi.enabled')) {
            return;
        }

        app()->singleton('nadi', function() {
            return \CleaniqueCoders\NadiLaravel\Transporter::make();
        });

        foreach (config('nadi.observe') as $event => $listeners) {
            foreach($listeners as $listener) {
                app()['events']->listen($event, $listener);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
