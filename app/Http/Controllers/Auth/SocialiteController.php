<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        return Socialite::driver('google')
        ->scopes(['https://www.googleapis.com/auth/calendar.readonly', 'https://www.googleapis.com/auth/calendar.events']) //leggere i calendari, creare-modificare-eliminare gli eventi
        ->with(['access_type' => 'offline'])
        ->redirect(); 
    }

    public function callback(Request $request): RedirectResponse
    {
        if(!$request->has('code')) return to_route('login');

        $google_user = Socialite::driver('google')->user();

        //TODO: come gestire lo scollegamento dell'utente registrato con social login?

        if(\Str::contains($google_user->getName(), ' ')){
            $first_name = \Str::before($google_user->getName(), ' ');
            $last_name = \Str::after($google_user->getName(), ' ');
        } else {
            $first_name = $google_user->getName();
            $last_name = '';
        }

        $user = User::updateOrCreate([
            'email' => $google_user->getEmail(),
        ], [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'google_id' => $google_user->getId(),
            'google_token' => $google_user->token,
            'google_refresh_token' => $google_user->refreshToken,
            'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
            'avatar' => $google_user->getAvatar(),
            'role_id' => Role::STUDIO,
            'email_verified_at' => now()->toDateTimeString(),
            'tos' => true,
            'privacy' => true,
        ]);

        Auth::login($user);

        if(auth()->user()->role_id === Role::SUPERADMIN){
            return to_route('superadmin.dashboard');
        } else {
            return to_route('dashboard');
        }
    }
}
