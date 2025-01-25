<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Permissions\Enums\PermissionEnum;
use Spatie\Permission\Models\Role;
use Modules\User\Models\User;
use Spatie\Permission\Models\Permission;
use Modules\Roles\Enums\RoleEnum;

class RolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions with module and guard_name columns using PermissionEnum
        $permissions = [
            ['name' => PermissionEnum::VIEW_DASHBOARD->value, 'module' => 'core', 'guard_name' => 'web'],
            ['name' => PermissionEnum::CREATE_POSTS->value, 'module' => 'posts', 'guard_name' => 'web'],
            ['name' => PermissionEnum::EDIT_POSTS->value, 'module' => 'posts', 'guard_name' => 'web'],
            ['name' => PermissionEnum::DELETE_POSTS->value, 'module' => 'posts', 'guard_name' => 'web'],
            ['name' => PermissionEnum::VIEW_USERS->value, 'module' => 'users', 'guard_name' => 'web'],
            ['name' => PermissionEnum::CREATE_USERS->value, 'module' => 'users', 'guard_name' => 'web'],
        ];

        // Create permissions with the module and guard_name columns
        foreach ($permissions as $permissionData) {
            Permission::create([
                'name' => $permissionData['name'],
                'module' => $permissionData['module'],
                'guard_name' => $permissionData['guard_name'],
            ]);
        }

        // Define roles and their permissions using RoleEnum
        $roles = [
            RoleEnum::MASTER->value => [
                PermissionEnum::VIEW_DASHBOARD->value,
                PermissionEnum::CREATE_POSTS->value,
                PermissionEnum::EDIT_POSTS->value,
                PermissionEnum::DELETE_POSTS->value,
                PermissionEnum::VIEW_USERS->value,
                PermissionEnum::CREATE_USERS->value,
            ],
            RoleEnum::AUTHOR->value => [
                PermissionEnum::VIEW_DASHBOARD->value,
                PermissionEnum::CREATE_POSTS->value,
                PermissionEnum::EDIT_POSTS->value,
            ]
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $permissions) {
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web', // Ensure the correct guard_name is set for each role
            ]);
            $role->givePermissionTo($permissions); // Assign permissions to role
        }

        // Optionally, assign a role to a specific user (e.g., user with ID 1)
        $roles = [RoleEnum::MASTER->value, RoleEnum::AUTHOR->value];

        // Loop through 10 users and assign a random role
        User::take(10)->get()->each(function ($user) use ($roles) {
            // Randomly pick a role
            $role = $roles[array_rand($roles)];
            
            // Assign the random role to the user
            $user->assignRole($role);
        });

        User::find(1)->assignRole(RoleEnum::MASTER->value);
    }
}