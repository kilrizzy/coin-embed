<?php

namespace App\Support\Services\Stripe;

use Illuminate\Support\Facades\Http;

class Stripe{

    private $apiKeyPrivate;
    private $apiURL = 'https://api.stripe.com';

    public function setCredentials($credentials = []){
        $this->apiKeyPrivate = $credentials['apiKeyPrivate'];
    }

    public function getCharges(){
        $response = Http::withBasicAuth($this->apiKeyPrivate,'')
            ->get($this->apiURL.'/v1/charges', []);
        return $response->object();
    }

    public function createCharge($data = []){
        $response = Http::withBasicAuth($this->apiKeyPrivate,'')
            ->asForm()
            ->post($this->apiURL.'/v1/charges', $data);
        return $response->object();
    }

}