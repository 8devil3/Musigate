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
        $has_google_calendar_scope = in_array(config('google-calendar.scope'), $user->google_scopes);
        $has_sync = $user->studio->booking_settings->has_sync;

        // Aggiorna il token di accesso e la data di scadenza nel database
        if($user && $has_sync && $has_google_calendar_scope && $user->google_refresh_token && Carbon::parse($user->google_token_expires_at)->isBefore(now())){
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
