<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Notifications\Studio\WelcomeGoogleUserNotification as StudioWelcomeNotification;
use App\Services\CreateStudioService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as GoogleUser;

class GoogleLoginController extends Controller
{
    public function redirect(): RedirectResponse
    {
        session()->put('user_from_route', url()->previous());

        return Socialite::driver('google')
            //leggere i calendari, CRUD degli eventi
            // ->scopes(['https://www.googleapis.com/auth/calendar'])
            ->with(['access_type' => 'offline'])
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if(!$request->has('code') || $request->has('denied')) return to_route('login');

        $google_user = Socialite::driver('google')->user();

        $user = User::where('email', $google_user->getEmail());

        //se l'utente esite accede direttamente
        if($user->exists()) return $this->login($user->firstOrFail(), $google_user);

        //se l'utente non esiste ma tenta l'accesso da login
        if(!$user->exists() && session()->get('user_from_route') === route('login')) return to_route('login')->with('login_fail', 'Account non presente');

        //se l'utente non esiste lo registro
        if(session()->get('registered_as') === 'studio'){
            $user = $this->register_studio($google_user);
            $user->notify(new StudioWelcomeNotification());
        } else if(session()->get('registered_as') === 'artist'){
            // $user = $this->register_artist($google_user);
        }

        //salvo l'avatar come png
        $scaled_image = Image::read(file_get_contents($google_user->getAvatar()))->scale(160, 160)->toPng();
        $path = 'users/user-' . $user->id . '/avatar/' . \Str::uuid() . '.png';
        Storage::disk('public')->put($path, $scaled_image);
        $user->update(['avatar' => $path]);

        Auth::login($user);

        return to_route('dashboard');
    }

    public function register_studio(GoogleUser $google_user): User
    {
        if(\Str::contains($google_user->getName(), ' ')){
            $first_name = ucwords(strtolower(\Str::before($google_user->getName(), ' ')));
            $last_name = ucwords(strtolower(\Str::after($google_user->getName(), ' ')));
        } else {
            $first_name = ucwords(strtolower($google_user->getName()));
            $last_name = '';
        }

        //nuovo utente
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
        CreateStudioService::store($user);

        session()->forget(['studio_step1', 'studio_step2', 'registered_as']);

        return $user;
    }

    public function register_artist(GoogleUser $google_user): void
    {
        //
    }

    public function login(User $user, $google_user): RedirectResponse
    {
        if(!$user->google_id) {
            //utente esite ma non ha mai acceduto con Google
            $user->update([
                'google_id' => $google_user->getId(),
                'google_token' => $google_user->token,
                'google_refresh_token' => $google_user->refreshToken,
                'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
                'google_scopes' => $google_user->approvedScopes,
            ]);
        } else {
            //utente esite ed ha già acceduto con Google
            $user->update([
                'google_token' => $google_user->token,
                'google_token_expires_at' => now()->addSeconds(intval($google_user->expiresIn))->toDateTimeString(),
                'google_scopes' => $google_user->approvedScopes,
            ]);
        }

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
            return to_route('password.request', ['email' => $user->email]);
        }

        return back()->with('success', 'Account Google scollegato');
    }
}
