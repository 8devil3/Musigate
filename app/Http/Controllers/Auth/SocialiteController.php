<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\CreateStudioService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
        ->scopes(['https://www.googleapis.com/auth/calendar']) //leggere i calendari, creare-modificare-eliminare gli eventi
        ->with(['access_type' => 'offline'])
        ->redirect(); 
    }

    public function callback(Request $request): RedirectResponse
    {
        if(!$request->has('code')) return to_route('home');

        $google_user = Socialite::driver('google')->user();

        //TODO: come gestire lo scollegamento dell'utente registrato con social login?

        if(\Str::contains($google_user->getName(), ' ')){
            $first_name = ucwords(strtolower(\Str::before($google_user->getName(), ' ')));
            $last_name = ucwords(strtolower(\Str::after($google_user->getName(), ' ')));
        } else {
            $first_name = ucwords(strtolower($google_user->getName()));
            $last_name = '';
        }

        $user = User::where('email', $google_user->getEmail());

        if(!$user->exists()){
            $user = User::create([
                'email' => $google_user->getEmail(),
                'first_name' => $first_name,
                'last_name' => $last_name,
                'google_id' => $google_user->getId(),
                'google_token' => $google_user->token,
                'google_refresh_token' => $google_user->refreshToken,
                'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
                'approved_scopes' => $google_user->approvedScopes,
                'email_verified_at' => now()->toDateTimeString(),
                'tos' => true,
                'privacy' => true,
            ]);

            if(session()->exists('studio_data')){
                //creazione nuovo studio
                $studio_data = session()->get('studio_data');
                CreateStudioService::store($user, $studio_data['step2']['name'], $studio_data['step2']['category'], $studio_data['step2']['vat']);
            } else if(session()->exists('artist_data')){
                //creazione nuovo artista
                $user->update(['role_id' => Role::ARTIST]);

                $artist_data = session()->get('artist_data');

                //TODO creazione dati artista
            }

            //salvo l'avatar come png
            $scaled_image = Image::read(file_get_contents($google_user->getAvatar()))->scale(160, 160)->toPng();
            $path = 'users/user-' . $user->id . '/avatar/' . \Str::uuid() . '.png';
            Storage::disk('public')->put($path, $scaled_image);
            $user->update(['avatar' => $path]);
        } else {
            $user->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'google_id' => $google_user->getId(),
                'google_token' => $google_user->token,
                'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
                'approved_scopes' => $google_user->approvedScopes,
            ]);

            $user = $user->firstOrFail();
        }

        session()->flush();

        Auth::login($user);

        return to_route('dashboard');
    }
}
