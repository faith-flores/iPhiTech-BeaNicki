<?php

namespace Database\Seeders\OldData;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserSeederNew extends Seeder
{
    public function run()
    {
        $data = File::get(database_path('data/old/users-old.json'));
        $data = json_decode($data, true);

        foreach ($data as $old) {
            DB::table('users')->insert([
                'uuid' => $old['pin_id'],
                'name' => $old['full_name'],
                'email' => $old['email'],
                'phone_number' => null,
                'email_verified_at' => $old['email_verified_at'],
                'password' => $old['password'],
                'is_super_admin' => 0,
                'active' => 0,
                'logged_in' => 0,
                'must_reset_password' => 0,
                'terms' => 0,
                'remember_token' => $old['remember_token'],
                'created_at' => $old['created_at'],
                'updated_at' => $old['updated_at'],
                'deleted_at' => null
            ]);
        }
    }
}
