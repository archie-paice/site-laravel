<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class VatsimOauthController extends Controller {
    public function redirect() {
        return Socialite::driver('vatsim')->redirect();
    }

    public function callback() {
        $user = Socialite::driver('vatsim')->user();

        $user = User::updateOrCreate([
            'id' => $user->cid
        ], [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'division' => $user->division,
            'facility' => $user->facility,
            'rating' => $user->rating
        ]);
    
        Auth::login($user);
    
        return redirect(route('home'));
    }

    public function logout() {
        Auth::logout();
        return redirect(route('home'));
    }
}