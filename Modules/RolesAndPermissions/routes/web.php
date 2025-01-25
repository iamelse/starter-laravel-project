<?php

use Illuminate\Support\Facades\Route;
use Modules\RolesAndPermissions\Http\Controllers\RolesAndPermissionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->middleware(['redirect.if.not.authenticated'])->group(function () {
    Route::prefix('roles-and-permissions')->group(function () {
        Route::get('/', [RolesAndPermissionsController::class, 'index'])
            ->middleware('can:view_roles_and_permissions')
            ->name('roles.and.permissions.index');

        Route::get('create', [RolesAndPermissionsController::class, 'create'])
            ->middleware('can:create_roles_and_permissions')
            ->name('roles.and.permissions.create');
        Route::post('/', [RolesAndPermissionsController::class, 'store'])
            ->middleware('can:create_roles_and_permissions')
            ->name('roles.and.permissions.store');

        Route::get('{role_and_permission}/edit', [RolesAndPermissionsController::class, 'edit'])
            ->middleware('can:edit_roles_and_permissions')
            ->name('roles.and.permissions.edit');
        Route::put('{role_and_permission}', [RolesAndPermissionsController::class, 'update'])
            ->middleware('can:edit_roles_and_permissions')
            ->name('roles.and.permissions.update');

        Route::delete('{role_and_permission}', [RolesAndPermissionsController::class, 'destroy'])
            ->middleware('can:delete_roles_and_permissions')
            ->name('roles.and.permissions.destroy');

        Route::post('save-table-settings', [RolesAndPermissionsController::class, 'saveTableSettings'])
            ->middleware('can:update_roles_and_permissions_table_settings')
            ->name('roles.and.permissions.save.table.settings');
    });
});