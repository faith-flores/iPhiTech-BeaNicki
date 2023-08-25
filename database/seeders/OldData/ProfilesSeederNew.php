<?php

namespace Database\Seeders\OldData;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfilesSeederNew extends Seeder
{
    public function run()
    {
        $data = File::get(database_path('data/old/users-old.json'));
        $data = json_decode($data, true);

        foreach ($data as $old) {
            DB::table('profiles')->insert([
                'account_id' => $old['id'],
                'user_id' => $old['id'],
                'created_at' => $old['created_at'],
                'updated_at' => $old['updated_at'],
                'billing_id' => null,
                'first_name' => $old['first_name'],
                'last_name' => $old['last_name'],
                'phone_number' => '',
                'is_profile_completed' => false,
                'status' => 1,
                'deleted_at' => null,
            ]);
        }
    }
}
