<?php

namespace Modules\RolesAndPermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions with module and guard_name columns
        $permissions = [
            ['name' => 'view_dashboard', 'module' => 'core', 'guard_name' => 'web'],
            ['name' => 'create_posts', 'module' => 'posts', 'guard_name' => 'web'],
            ['name' => 'edit_posts', 'module' => 'posts', 'guard_name' => 'web'],
            ['name' => 'delete_posts', 'module' => 'posts', 'guard_name' => 'web'],
            ['name' => 'view_users', 'module' => 'users', 'guard_name' => 'web'],
            ['name' => 'create_users', 'module' => 'users', 'guard_name' => 'web'],
            ['name' => 'can_update_user_table_settings', 'module' => 'users', 'guard_name' => 'web'],

            ['name' => 'can_view_roles', 'module' => 'roles_and_permissions', 'guard_name' => 'web'],
            ['name' => 'can_update_roles_table_settings', 'module' => 'roles_and_permissions', 'guard_name' => 'web'],
        ];

        // Create permissions with the module and guard_name columns
        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate([
                'name' => $permissionData['name'],
                'guard_name' => $permissionData['guard_name'],
            ], [
                'module' => $permissionData['module'],
            ]);
        }

        // Define roles and their permissions
        $roles = [
            'Master' => [
                'view_dashboard',
                'create_posts',
                'edit_posts',
                'delete_posts',
                'view_users',
                'create_users',
                'can_update_user_table_settings',

                'can_view_roles',
                'can_update_roles_table_settings',
            ],
            'Author' => [
                'view_dashboard',
                'create_posts',
                'edit_posts',
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