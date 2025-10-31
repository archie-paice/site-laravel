<?php

use App\Http\Controllers\RosterController;
use App\Http\Controllers\Auth\VatsimOauthController;
use App\Http\Controllers\UserController;
use App\Jobs\SyncRoster;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

# Oauth
Route::get('/auth/redirect', VatsimOauthController::class . '@redirect')->name('auth.redirect');
Route::get('/auth/callback', VatsimOauthController::class . '@callback')->name('auth.callback');
Route::get('/auth/logout', VatsimOauthController::class . '@logout')->name('auth.logout');

Route::resource('users', UserController::class);
Route::get('/roster', RosterController::class . '@index')->name('roster');

if (App::environment('development', 'local')) {
    Route::get('/sync', function() {
        SyncRoster::dispatch();
        return 'scheduled';
    });
}