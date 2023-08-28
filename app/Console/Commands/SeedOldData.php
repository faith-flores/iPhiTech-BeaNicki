<?php

namespace App\Console\Commands;

use Database\Seeders\Production\OldDataSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-old-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will seed users, and user skills data from v1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('db:seed', ['--class' => OldDataSeeder::class]);
    }
}
