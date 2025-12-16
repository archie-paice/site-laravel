<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\Auth\VatsimOauthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Jobs\SyncRoster;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventPositionPresetController;
use App\Http\Controllers\EventFieldController;
use App\Http\Controllers\ManageEventController;

Route::get('/', function () {
    return view('home');
})->name('home');

# Oauth
Route::get('/auth/redirect', VatsimOauthController::class . '@redirect')->name('auth.redirect');
Route::get('/auth/callback', VatsimOauthController::class . '@callback')->name('auth.callback');
Route::get('/auth/logout', VatsimOauthController::class . '@logout')->name('auth.logout');

Route::resource('users', UserController::class);
Route::prefix('admin')->group(function () {
    Route::resource('/', AdminController::class)->middleware('permission:view dashboard')->names('admin');
    
    Route::middleware('permission:manage events')->group(function () {
        Route::resource('event-fields', EventFieldController::class)->names('event-fields');
        Route::resource('position-presets', EventPositionPresetController::class)->names('position-presets');
        Route::resource('events', ManageEventController::class)->names('manage-events');
    });
});


Route::get('/roster', RosterController::class . '@index')->name('roster');

if (App::environment('development', 'local')) {
    Route::get('/sync', function() {
        SyncRoster::dispatch();
        return 'scheduled';
    });
}