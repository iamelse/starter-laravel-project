<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Web\UserController;

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
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('can:view_users')
            ->name('user.index');

        Route::get('create', [UserController::class, 'create'])
            ->middleware('can:view_users')
            ->name('user.create');
        Route::post('/', [UserController::class, 'store'])
            ->middleware('can:view_users')
            ->name('user.store');
            
        Route::get('{user}/edit', [UserController::class, 'edit'])
            ->middleware('can:edit_users')
            ->name('user.edit');
        Route::put('{user}', [UserController::class, 'update'])
            ->middleware('can:edit_users')
            ->name('user.update');

        Route::delete('{user}', [UserController::class, 'destroy'])
            ->middleware('can:delete_users')     
            ->name('user.destroy');

        Route::post('save-table-settings', [UserController::class, 'saveTableSettings'])
            ->middleware('can:view_users')
            ->name('user.save.table.settings');
    });
});