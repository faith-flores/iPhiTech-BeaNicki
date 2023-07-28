<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = base_path('database/data/roles-and-permissions.json');

        if (file_exists($file_path)) {
            $data = File::get($file_path);
            $data = json_decode($data, true);

            foreach ($data as $role_slug => $permissions) {
                $role = Role::query()->where("name", "=", $role_slug)->first();

                if (! $role) {
                    $role = Role::create(["name" => $role_slug]);
                }

                foreach ($permissions as $permission_name) {
                    $permission = Permission::findOrCreate($permission_name);
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
