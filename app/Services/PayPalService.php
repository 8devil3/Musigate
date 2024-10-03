<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class PayPalService
{
    public static function get_access_token()
    {
        $client_ID = app()->environment('production') ? config('services.paypal.client_id') : config('services.paypal_sandbox.client_id');
        $client_secret = app()->environment('production') ? config('services.paypal.client_secret') : config('services.paypal_sandbox.client_secret');

        $response =  Http::asForm()->withBasicAuth($client_ID, $client_secret)
            ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
            ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ])->getBody();

        return json_decode($response);
    }
}