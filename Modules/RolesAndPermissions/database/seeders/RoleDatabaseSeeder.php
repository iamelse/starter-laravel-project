<?php

namespace Modules\RolesAndPermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles and their permissions
        $roles = [
            'Master' => [
                'view_dashboard',
                
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
                'update_user_table_settings',

                'view_roles_and_permissions',
                'create_roles_and_permissions',
                'edit_roles_and_permissions',
                'delete_roles_and_permissions',
                'update_roles_and_permissions_table_settings',
            ],
            'Author' => [
                'view_dashboard',
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::updateOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
            $role->syncPermissions($rolePermissions); // Assign permissions to role
        }

        // Assign roles to users
        $users = User::take(10)->get();
        $roleNames = array_keys($roles);

        foreach ($users as $user) {
            $randomRole = $roleNames[array_rand($roleNames)];
            $user->assignRole($randomRole);
        }

        // Assign master role to user with ID 1
        $adminUser = User::find(1);
        if ($adminUser) {
            $adminUser->assignRole('Master');
        }
    }
}