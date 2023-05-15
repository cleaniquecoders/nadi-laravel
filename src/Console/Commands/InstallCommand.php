<?php

namespace CleaniqueCoders\NadiLaravel\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nadi:install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Nadi for Laravel';

    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'nadi-config',
            '--force' => $this->option('force') ?? false,
        ]);
        $this->info('Successfully installed Nadi');
    }
}
