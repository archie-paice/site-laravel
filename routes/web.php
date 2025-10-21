<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;





Route::get('/', function () {
    return view('home');
})->name('home');


# Oauth
Route::get('/auth/redirect', function() {
    return Socialite::driver('vatsim')->redirect();
})->middleware('web');

# Oauth
Route::get('/auth/callback', function() {
    $user = Socialite::driver('vatsim')->user();

    $user = User::updateOrCreate([
        'id' => $user->cid
    ], [
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email
    ]);
 
    Auth::login($user);
 
    return redirect('home');
})->middleware('web');