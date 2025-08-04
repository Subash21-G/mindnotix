<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UserFormSchemaManager; // Make sure this is the correct namespace!

class CreateBaseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-base-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create users and forms tables using UserFormSchemaManager';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        UserFormSchemaManager::createUsersTable();
        UserFormSchemaManager::createFormsTable();
        $this->info('Users and forms tables have been created (if needed).');
        return 0;
    }
}
