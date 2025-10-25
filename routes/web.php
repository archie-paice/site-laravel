<?php

use App\Http\Controllers\Auth\VatsimOauthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

# Oauth
Route::get('/auth/redirect', VatsimOauthController::class . '@redirect')->name('auth.redirect');
Route::get('/auth/callback', VatsimOauthController::class . '@callback')->name('auth.callback');
Route::get('/auth/logout', VatsimOauthController::class . '@logout')->name('auth.logout');

Route::resource('users', UserController::class);