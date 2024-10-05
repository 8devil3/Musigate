<?php

namespace App\Services;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class PayPalService
{
    protected $client_id, $client_secret, $base_auth_endpoint, $token_endpoint, $redirect_uri, $user_info_endpoint;

    public function __construct()
    {
        if(app()->environment('production')){
            $this->client_id = config('services.paypal.client_id');
            $this->client_secret = config('services.paypal.client_secret');
            $this->base_auth_endpoint = config('services.paypal.base_auth_endpoint');
            $this->token_endpoint = config('services.paypal.token_endpoint');
            $this->redirect_uri = config('services.paypal.redirect');
            $this->user_info_endpoint = config('services.paypal.user_info_endpoint');
        } else {
            $this->client_id = config('services.paypal_sandbox.client_id');
            $this->client_secret = config('services.paypal_sandbox.client_secret');
            $this->base_auth_endpoint = config('services.paypal_sandbox.base_auth_endpoint');
            $this->token_endpoint = config('services.paypal_sandbox.token_endpoint');
            $this->redirect_uri = config('services.paypal_sandbox.redirect');
            $this->user_info_endpoint = config('services.paypal_sandbox.user_info_endpoint');
        }
    }

    public function redirect(): RedirectResponse
    {
        $params = http_build_query([
            'client_id' => $this->client_id,
            'scope' => 'openid email profile https://uri.paypal.com/services/invoicing',
            'redirect_uri' => $this->redirect_uri,
        ]);

        return redirect()->away($this->base_auth_endpoint . '&' . $params);
    }

    public function get_access_token(string $code)
    {
        $response =  Http::asForm()->withBasicAuth($this->client_id, $this->client_secret)
            ->post($this->token_endpoint, [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ])->getBody();

        return json_decode($response);
    }

    public function refresh_token(string $refresh_token)
    {
        $response =  Http::asForm()->withBasicAuth($this->client_id, $this->client_secret)
            ->post($this->token_endpoint, [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
            ])->getBody();

        return json_decode($response);
    }

    public function user_info(string $access_token)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/x-www-form-urlencoded'  
        ])->get($this->user_info_endpoint);

        return json_decode($response);
    }
}