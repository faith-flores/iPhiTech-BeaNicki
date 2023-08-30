<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Seeder;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //make an admin role
        $admin_role = false;
        try {
            $admin_role = Role::findByName('Admin');
        } catch (RoleDoesNotExist $e) {
        } finally {
            if (! $admin_role) {
                $admin_role = Role::create(['name' => 'Admin']);
            }
        }

        /**
         * @var Role $admin_role
         */

        //make the super admin user
        $this->createUser([
            'name' => 'Super Admin',
            'email' => 'superadmin@domain.com',
            'is_super_admin' => true,
        ], $admin_role);

        //make the admin user
        $this->createUser([
            'name' => 'Admin',
            'email' => 'admin@domain.com',
        ], $admin_role);
    }

    /**
     * @return bool|User
     */
    private function createUser($data, Role $role)
    {
        if (! User::query()->where('email', $data['email'])->exists()) {
            /**
             * @var User $user
             */
            $user = User::factory()->create($data);

            event(new Registered($user));

            $user->syncRoles($role);

            return $user;
        }

        return false;
    }
}
