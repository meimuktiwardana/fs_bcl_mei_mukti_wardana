<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

// app/Console/Commands/SetupProject.php
class SetupProject extends Command
{
    protected $signature = 'setup:project';
    protected $description = 'Setup project dengan migration dan seeder';

    public function handle()
    {
        $this->info('Setting up project...');
        
        $this->call('migrate:fresh');
        $this->call('db:seed');
        
        $this->info('Project setup completed!');
    }
}
