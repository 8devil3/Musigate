<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\CreateStudioService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $redirect = route('google.register.studio');
        if(url()->previous() === route('login')) $redirect = route('google.login');
        // if(url()->previous() === route('register')) $redirect = route('google.register.artist');

        return Socialite::driver('google')
            //leggere i calendari, creare-modificare-eliminare gli eventi
            // ->scopes(['https://www.googleapis.com/auth/calendar'])
            ->with(['access_type' => 'offline'])
            ->redirectUrl($redirect)
            ->redirect();
    }

    public function login(Request $request): RedirectResponse
    {
        if(!$request->has('code') || $request->has('denied')) return to_route('login');

        try {
            $google_user = Socialite::driver('google')->redirectUrl(route('google.login'))->user();
        } catch (\Exception $e) {
            return to_route('login');
        }

        //TODO: come gestire lo scollegamento dell'utente registrato con social login?

        $user = User::where('email', $google_user->getEmail());

        //se l'utente non esiste lo reindirizzo alla pagina di login con l'errore
        if(!$user->exists()) return to_route('login')->with('login_fail', 'Nessun utente con l\'email utilizzata. Per accedere effettua prima la registrazione.');

        if(!$user->firstOrFail()->google_id) {
            //utente esite ma non ha mai acceduto con Google
            $user->update([
                'google_id' => $google_user->getId(),
                'google_token' => $google_user->token,
                'google_refresh_token' => $google_user->refreshToken,
                'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
                'google_scopes' => $google_user->approvedScopes,
            ]);

            $user = $user->firstOrFail();
        } else {
            //utente esite ed ha già acceduto con Google
            $user->update([
                'google_token' => $google_user->token,
                'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
                'google_scopes' => $google_user->approvedScopes,
            ]);

            $user = $user->firstOrFail();
        }

        Auth::login($user);

        return to_route('dashboard');
    }

    public function register_studio(Request $request): RedirectResponse
    {
        if(!$request->has('code') || $request->has('denied')) return to_route('login');

        $google_user = Socialite::driver('google')->user();

        $user = User::where('email', $google_user->getEmail());

        if($user->exists()) return to_route('login');

        //TODO: come gestire lo scollegamento dell'utente registrato con social login?

        if(\Str::contains($google_user->getName(), ' ')){
            $first_name = ucwords(strtolower(\Str::before($google_user->getName(), ' ')));
            $last_name = ucwords(strtolower(\Str::after($google_user->getName(), ' ')));
        } else {
            $first_name = ucwords(strtolower($google_user->getName()));
            $last_name = '';
        }

        //utente nuovo
        $user = User::create([
            'email' => $google_user->getEmail(),
            'email_verified_at' => now()->toDateTimeString(),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'google_id' => $google_user->getId(),
            'google_token' => $google_user->token,
            'google_refresh_token' => $google_user->refreshToken,
            'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
            'google_scopes' => $google_user->approvedScopes,
            'tos' => true,
            'privacy' => true,
        ]);

        //creazione nuovo studio
        $studio_data = session()->get('studio_data');
        CreateStudioService::store($user, $studio_data['step2']['name'], $studio_data['step2']['category'], $studio_data['step2']['vat']);

        //salvo l'avatar come png
        $scaled_image = Image::read(file_get_contents($google_user->getAvatar()))->scale(160, 160)->toPng();
        $path = 'users/user-' . $user->id . '/avatar/' . \Str::uuid() . '.png';
        Storage::disk('public')->put($path, $scaled_image);
        $user->update(['avatar' => $path]);

        session()->flush();

        Auth::login($user);

        return to_route('dashboard');
    }

    public function register_artist(Request $request): RedirectResponse
    {
        if(!$request->has('code') || $request->has('denied')) return to_route('login');

        $google_user = Socialite::driver('google')->user();

        $user = User::where('email', $google_user->getEmail());

        if($user->exists()) return to_route('login');

        //TODO: come gestire lo scollegamento dell'utente registrato con social login?

        if(\Str::contains($google_user->getName(), ' ')){
            $first_name = ucwords(strtolower(\Str::before($google_user->getName(), ' ')));
            $last_name = ucwords(strtolower(\Str::after($google_user->getName(), ' ')));
        } else {
            $first_name = ucwords(strtolower($google_user->getName()));
            $last_name = '';
        }

        //utente nuovo
        $user = User::create([
            'role_id' => Role::ARTIST,
            'email' => $google_user->getEmail(),
            'email_verified_at' => now()->toDateTimeString(),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'google_id' => $google_user->getId(),
            'google_token' => $google_user->token,
            'google_refresh_token' => $google_user->refreshToken,
            'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
            'google_scopes' => $google_user->approvedScopes,
            'tos' => true,
            'privacy' => true,
        ]);

        //TODO creazione dati artista
        $artist_data = session()->get('artist_data');


        //salvo l'avatar come png
        $scaled_image = Image::read(file_get_contents($google_user->getAvatar()))->scale(160, 160)->toPng();
        $path = 'users/user-' . $user->id . '/avatar/' . \Str::uuid() . '.png';
        Storage::disk('public')->put($path, $scaled_image);
        $user->update(['avatar' => $path]);

        session()->flush();

        Auth::login($user);

        return to_route('dashboard');
    }

    public function revoke_token(): RedirectResponse
    {
        $user = auth()->user();

        $google_client = new \Google\Client();

        $token = $user->google_token;
        if(Carbon::parse($user->google_token_expires_at)->isBefore(now())) $token = $user->google_refresh_token;  

        $google_client->revokeToken($token);

        $user->update([
            'google_id' => null,
            'google_token' => null,
            'google_token_expires_at' => null,
            'google_refresh_token' => null,
            'google_scopes' => null,
        ]);

        if(!$user->password){
            Auth::logout();
            return to_route('password.request');
        }

        return back()->with('success', 'Account Google scollegato');
    }
}
