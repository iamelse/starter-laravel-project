<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

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

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'index'])
            ->middleware('redirect.if.authenticated')
            ->name('auth.index');
    
    Route::post('login', [AuthController::class, 'login'])
            ->middleware('redirect.if.authenticated')
            ->name('auth.login');
    
    Route::post('logout', [AuthController::class, 'logout'])
            ->middleware('redirect.if.not.authenticated')
            ->name('auth.logout');
});
