<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Profile\Http\Controllers\ProfileController;

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

Route::middleware(['redirect.if.not.authenticated'])->group(function () {
    Route::prefix('profile/{username}')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])
            ->middleware('can:view_profile')
            ->name('show.profile');
        Route::put('update', [ProfileController::class, 'update'])
            ->middleware('can:update_profile')
            ->name('update.profile');
    });
});