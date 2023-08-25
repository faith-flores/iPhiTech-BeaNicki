<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\OldData\AccountSeeder;
use Database\Seeders\OldData\AddressSeeder;
use Database\Seeders\OldData\UserSeederNew;
use Database\Seeders\OldData\ProfilesSeederNew;
use Database\Seeders\OldData\PasswordResetsSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OldDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeederNew::class,
            AccountSeeder::class,
            AddressSeeder::class,
            PasswordResetsSeeder::class,
            ProfilesSeederNew::class,
        ]);
    }
}
