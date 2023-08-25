<?php

namespace Database\Seeders\OldData;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PasswordResetsSeeder extends Seeder
{
    public function run()
    {
        $data = File::get(database_path('data/old/password-resets-old.json'));
        $data = json_decode($data, true);

        foreach ($data as $old) {
            DB::table('password_resets')->insert([
                'email' => $old['email'],
                'token' => $old['token'],
                'created_at' => $old['created_at'],
            ]);
        }
    }
}
