<?php

namespace CleaniqueCoders\NadiLaravel;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NadiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('nadi')
            ->hasConfigFile()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile();
            });

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
