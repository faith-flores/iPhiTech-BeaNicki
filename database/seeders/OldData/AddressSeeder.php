<?php

namespace Database\Seeders\OldData;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $data = File::get(database_path('data/old/users-old.json'));
        $data = json_decode($data, true);

        foreach ($data as $old) {
            DB::table('addresses')->insert([
                'created_at' => $old['created_at'],
                'updated_at' => $old['updated_at'],
                'deleted_at' => null,
                'addressable_type' => null,
                'addressable_id' => null,
                'address_line_1' => $old['address_1'],
                'address_line_2' => $old['address_2'],
                'street' => $old['street'],
                'city' => $old['city'],
                'province' => $old['province'],
                'country' => null,
                'zip_code' => $old['zip_code'],
                'address_type' => null,
            ]);
        }
    }
}
