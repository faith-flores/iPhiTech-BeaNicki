<?php

namespace Database\Seeders;

use App\Events\UserRegistered;
use App\Models\User;
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

            foreach ($data as $list) {
                $role = Role::query()->where("name", "=", $list['role'])->first();

                if ($role) {
                    foreach ($list['users'] as $email) {
                        $this->createUser($email, $role);
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
    private function createUser($email, Role $role)
    {
        if (!User::query()->where('email',  $email)->exists()) {
            /**
             * @var User $user
             */
            $user = User::factory()->verified()->create(['email' => $email]);
            $user->syncRoles($role);

            event(new UserRegistered($user, $role->name));

            return $user;
        }

        return false;
    }
}
