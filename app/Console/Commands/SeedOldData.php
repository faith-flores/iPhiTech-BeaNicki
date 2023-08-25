<?php

namespace App\Console\Commands;

use Database\Seeders\OldDataSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:old-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run seeders for migrating data from old sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('db:seed', ['--class' => OldDataSeeder::class]);

        // $seedersPath = database_path('seeders/old-data');

        // $seeders = scandir($seedersPath);
        // foreach ($seeders as $seeder) {
        //     if (pathinfo($seeder, PATHINFO_EXTENSION) === 'php') {
        //         $seederClassName = pathinfo($seeder, PATHINFO_FILENAME);

        //     }
        // }

        $this->info('Seed old data successfully!');
    }
}
