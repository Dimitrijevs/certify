<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class certifySetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certify:setup';

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
        $this->info('Starting Certify setup...');

        // Copy .env file if it doesn't exist
        if (!file_exists(base_path('.env'))) {
            $this->info('Creating .env file...');
            copy(base_path('.env.example'), base_path('.env'));
            $this->info('✅ .env file created successfully');
        } else {
            $this->info('⚠️ .env file already exists, skipping');
        }

        // Generate application key
        $this->info('Generating application key...');
        $this->call('key:generate');
        $this->info('✅ Application key generated successfully');

        // Create storage symbolic link
        $this->info('Creating storage link...');
        $this->call('storage:link');
        $this->info('✅ Storage link created successfully');

        // Install NPM dependencies
        $this->info('Installing NPM dependencies...');
        $this->line('This might take a while...');
        exec('npm install');
        $this->info('✅ NPM dependencies installed');

        // Build frontend assets
        $this->info('Building frontend assets...');
        exec('npm run build');
        $this->info('✅ Frontend assets built successfully');

        // Run migrations
        $this->info('Running database migrations...');
        $this->call('migrate');
        $this->info('✅ Database migrations completed successfully');

        // Run certify:fresh command
        $this->info('Finalizing setup with certify:fresh...');
        $this->call('certify:fresh');
        $this->info('✅ Database seeded successfully');

        // Run optimize command
        $this->info('Optimizing application...');
        $this->call('optimize');
        $this->info('✅ Application optimized successfully');

        $this->newLine();
        $this->info('🚀 Certify setup completed successfully!');
        $this->info('Run "php artisan serve" to start the development server.');
    }
}
