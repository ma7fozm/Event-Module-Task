<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->name('auth.')->group(function () {

    /* Authentication Routes */
    Route::post('user/login', [AuthController::class, 'userLogin'])->name('login');
    Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');

    Route::group(['middleware' => 'auth:admin-api'], function () {

        /* Profile Routes */
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    });
});

Route::group(['middleware' => ['auth:api','scopes:user']], function () {
    /* Events Routes */
    Route::post('events/create', [EventController::class, 'createEvent'])->name('event.create');
});

Route::group(['middleware' => ['auth:admin-api','scopes:admin']], function () {

    /* Events Routes */
    Route::get('events', [EventController::class, 'getEvents'])->name('event.all');
    Route::get('events/approve/{id}', [EventController::class, 'approveEvent'])->name('event.approve');
});
