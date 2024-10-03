<?php

namespace App\Services;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class PayPalService
{
    protected $client_id, $client_secret, $base_auth_endpoint, $token_endpoint, $redirect_uri, $params;

    public function __construct()
    {
        if(app()->environment('production')){
            $this->client_id = config('services.paypal.client_id');
            $this->client_secret = config('services.paypal.client_secret');
            $this->base_auth_endpoint = config('services.paypal.base_auth_endpoint');
            $this->token_endpoint = config('services.paypal.token_endpoint');
            $this->redirect_uri = config('services.paypal.redirect');
        } else {
            $this->client_id = config('services.paypal_sandbox.client_id');
            $this->client_secret = config('services.paypal_sandbox.client_secret');
            $this->base_auth_endpoint = config('services.paypal_sandbox.base_auth_endpoint');
            $this->token_endpoint = config('services.paypal_sandbox.token_endpoint');
            $this->redirect_uri = config('services.paypal_sandbox.redirect');
        }
    }

    public function auth_endpoint()
    {
        $this->params = http_build_query([
            'client_id' => $this->client_id,
            'scope' => 'openid',
            'redirect_uri' => $this->redirect_uri,
        ]);

        return $this;
    }

    public function redirect(): RedirectResponse
    {
        return redirect()->away($this->params);
    }

    public function get_access_token(string $code)
    {
        $base64_auth = base64_encode($this->client_id . ':' . $this->client_secret);

        $response =  Http::asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $base64_auth,
            ])
            ->post($this->token_endpoint, [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ])->getBody();

        return json_decode($response);
    }
}