<?php

namespace CleaniqueCoders\NadiLaravel\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nadi:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test connectivity to Nadi API';

    public function handle()
    {
        $this->info('Connectivity to Nadi API is: '.(
            app('nadi')->test() ? 'Active' : 'Inactive')
        );
    }
}
