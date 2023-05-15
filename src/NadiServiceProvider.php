<?php

namespace CleaniqueCoders\NadiLaravel;

use CleaniqueCoders\NadiLaravel\Console\Commmands\InstallCommand;
use CleaniqueCoders\NadiLaravel\Console\Commmands\TestCommand;
use CleaniqueCoders\NadiLaravel\Console\Commmands\VerifyCommand;
use Illuminate\Support\ServiceProvider;

class NadiServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/../config/nadi.php' => config_path('nadi.php'),
        ], 'nadi-config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/nadi.php', 'nadi'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                // TestCommand::class, // @todo
                // VerifyCommand::class, // @todo
            ]);
        }

        if (! config('nadi.enabled')) {
            return;
        }

        app()->singleton('nadi', function () {
            return \CleaniqueCoders\NadiLaravel\Transporter::make();
        });

        foreach (config('nadi.observe') as $event => $listeners) {
            foreach ($listeners as $listener) {
                app()['events']->listen($event, $listener);
            }
        }

    }
}
