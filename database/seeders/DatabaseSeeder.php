<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUsersSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(PicklistsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(SkillsSeeder::class);

        /**
         * TODO: Create Subscription, Plans
         * TODO: Add default subscription
         * TODO: Settings for Subscription Features
         */
    }
}
