<?php

namespace CleaniqueCoders\NadiLaravel\Console\Commmands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nadi:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Nadi for Laravel';

    public function handle()
    {
        $this->call('vendor:publish --tag=nadi-config');
        $this->info('Successfully installed Nadi');
    }
}
