<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class CertifyNow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certify:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (App::environment('local', 'development')) {
            $this->call('migrate:fresh', [
                '--seeder' => 'DatabaseSeeder',
            ]);
        } else {
            $this->error('This command can only be run in a development environment.');
        }

        return 0;
    }
}
