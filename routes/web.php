<?php

use App\Http\Controllers\RosterController;
use App\Http\Controllers\Auth\VatsimOauthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StatisticsPrefixesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Training\TrainingAssignmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserManagementController;
use App\Jobs\SyncRoster;
use App\Jobs\UpdateOnlineControllers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

# Oauth
Route::get('/auth/redirect', VatsimOauthController::class . '@redirect')->name('auth.redirect');
Route::get('/auth/callback', VatsimOauthController::class . '@callback')->name('auth.callback');
Route::get('/auth/logout', VatsimOauthController::class . '@logout')->name('auth.logout');

Route::resource('users', UserController::class, ['only' => ['show', 'edit', 'update']]);
Route::get('profile', UserController::class.'@index')->name('profile');
Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');

Route::post('training-assignment/create', [TrainingAssignmentController::class, 'create'])->middleware('auth')->name('training-assignment.create');

Route::prefix('admin')->middleware('permission:view dashboard')->group(function() {
    Route::get('/', DashboardController::class.'@index')->name('admin.index');
    Route::get('users', UserManagementController::class.'@index')->name('users.index');

    Route::middleware('permission:manage statistics prefixes')->group(function() {
        Route::resource('statistics-prefixes', StatisticsPrefixesController::class);
    });

    Route::prefix('/training')->middleware('role:training')->group(function() {
        Route::resource('assignments', TrainingAssignmentController::class, ['only' => ['update', 'edit', 'destroy', 'index']])->names('training-assignment');
    });
});



Route::get('/roster', RosterController::class . '@index')->name('roster');

if (App::environment('development', 'local')) {
    Route::get('/sync', function() {
        SyncRoster::dispatch();
        return 'scheduled';
    });

    Route::get('/online', function() {
        UpdateOnlineControllers::dispatch();
    });
}
