<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect(); 
    }

    public function callback(Request $request): RedirectResponse
    {
        if (!$request->has('code')) return to_route('login');

        $google_user = Socialite::driver('google')->user();

        //TODO: come gestire lo scollegamento dell'utente registrato con social login?

        $user = User::updateOrCreate([
            'email' => $google_user->email,
        ], [
            'google_id' => $google_user->id,
            'google_token' => $google_user->token,
            'google_refresh_token' => $google_user->refreshToken,
            'role_id' => Role::STUDIO,
            'email_verified_at' => now()->toDateTimeString(),
            'tos' => true,
            'privacy' => true,
        ]);

        // if(!$user->candidate){
        //     if(\Str::contains($google_user->name, ' ')){
        //         $first_name = \Str::before($google_user->name, ' ');
        //         $last_name = \Str::after($google_user->name, ' ');
        //     } else {
        //         $first_name = $google_user->name;
        //         $last_name = '';
        //     }

        //     Candidate::create([
        //         'user_id' => $user->id,
        //         'first_name' => $first_name,
        //         'last_name' => $last_name,
        //     ]);
        // }

        Auth::login($user);

        if(auth()->user()->role_id === Role::SUPERADMIN){
            return to_route('superadmin.dashboard');
        } else {
            return to_route('studio.dashboard');
        }
    }
}
