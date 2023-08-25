<?php

namespace Database\Seeders\OldData;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $data = File::get(database_path('data/old/users-old.json'));
        $data = json_decode($data, true);

        foreach ($data as $old) {
            DB::table('profiles_accounts')->insert([
                'owner_user_id' => $old['id'],
                'created_at' => $old['created_at'],
                'updated_at' => $old['updated_at'],
                'account_type' => 0,
                'company_name' => null,
                'email' => $old['email'],
                'company_phone' => null,
                'web_url' => null,
                'is_active' => 0,
                'is_multi_user' => 0,
            ]);
        }
    }
}
