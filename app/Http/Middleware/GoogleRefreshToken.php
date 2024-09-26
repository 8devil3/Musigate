<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
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

        // Aggiorna il token di accesso e la data di scadenza nel database
        if($user && $user->google_id && $user->google_refresh_token && Carbon::parse($user->google_token_expires_at)->lessThan(now())){
            $refreshed_google_tokens = Socialite::driver('google')->refreshToken($user->google_refresh_token);

            $user->update([
                'google_token' => $refreshed_google_tokens->token,
                'google_refresh_token' => $refreshed_google_tokens->refreshToken,
                'google_token_expires_at' => now()->addSeconds(intval($refreshed_google_tokens->expiresIn)),
            ]);
        }

        return $next($request);
    }
}