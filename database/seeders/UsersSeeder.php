<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //make demo users
        $file_path = base_path('database/data/users.json');

        if (file_exists($file_path)) {
            $data = File::get($file_path);
            $data = json_decode($data, true);

            foreach ($data as $role_slug => $users) {
                $role = Role::query()->where("name", "=", $role_slug)->first();

                if ($role) {
                    foreach ($users as $user) {
                        $this->createUser($user, $role);
                    }
                }
            }
        }
    }

    /**
     * @param      $data
     * @param Role $role
     *
     * @return bool|User
     */
    private function createUser($data, Role $role)
    {
        if (!User::query()->where('email',  $data['email'])->exists()) {
            /**
             * @var User $user
             */
            $user = User::factory()->create($data);
            $user->syncRoles($role);

            return $user;
        }

        return false;
    }
}
