<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedBackup1 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sql = file_get_contents('database/backups/beanicky-backup.sql');
        DB::unprepared($sql);
    }
}
 