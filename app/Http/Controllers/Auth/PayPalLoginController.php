<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PayPalLoginController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return (new PayPalService)->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if(!$request->has('code')) return to_route('bookings.settings.edit');

        $paypal_user = (new PayPalService)->get_access_token($request->code);

        $account_type = (new PayPalService)->user_info($paypal_user->access_token)->account_type;

        if($account_type !== 'BUSINESS'){
            return to_route('bookings.settings.edit')->with('error', 'Il tuo account PayPal non è di tipo Business.');
        }

        $user = auth()->user();

        $user->update([
            'paypal_token_type' => $paypal_user->token_type,
            'paypal_access_token' => $paypal_user->access_token,
            'paypal_refresh_token' => $paypal_user->refresh_token,
            'paypal_access_token_expiration_at' => now()->addSeconds(intval($paypal_user->expires_in))->toDateTimeString(),
            'paypal_scopes' => explode(' ', $paypal_user->scope),
            'paypal_nonce' => $paypal_user->nonce,
        ]);

        return to_route('bookings.settings.edit');
    }
}
