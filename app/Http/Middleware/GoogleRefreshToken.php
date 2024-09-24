<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class GoogleRefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if($user && $user->google_id && $user->google_refresh_token && now()->addSeconds($user->google_token_expires_in)->lessThan(now())){
            $google_client = Socialite::driver('google')->getAccessTokenResponse($user->google_refresh_token);

            // Aggiorna il token di accesso e la data di scadenza nel database
            $user->google_token = $google_client['access_token'];
            $user->google_token_expires_in = $google_client['expires_in'];
            $user->save();
        }

        return $next($request);
    }
}
