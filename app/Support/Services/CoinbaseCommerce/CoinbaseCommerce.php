<?php

namespace App\Support\Services\CoinbaseCommerce;

use Illuminate\Support\Facades\Http;

class CoinbaseCommerce{

    private $apiKeyPrivate;
    private $apiURL = 'https://api.commerce.coinbase.com';

    public function setCredentials($credentials = []){
        $this->apiKeyPrivate = $credentials['apiKeyPrivate'];
    }

    public function getCharges(){
        $response = Http::withHeaders([
            'X-CC-Api-Key' => $this->apiKeyPrivate
        ])
            ->get($this->apiURL.'/charges', []);
        return $response->object();
    }

    public function createCharge($attributes=[]){
        $response = Http::withHeaders([
            'X-CC-Api-Key' => $this->apiKeyPrivate
        ])
            ->post($this->apiURL.'/charges', $attributes);
        return $response->object();
    }

    public function getCharge($chargeId){
        $response = Http::withHeaders([
            'X-CC-Api-Key' => $this->apiKeyPrivate
        ])
            ->get($this->apiURL.'/charges/'.$chargeId, []);
        return $response->object();
    }

    public static function getStatusFromChargeStatus($chargeStatus){
        if(empty($chargeStatus)){
            return null;
        }
        // missing: UNRESOLVED, RESOLVED
        if($chargeStatus == 'COMPLETED'){
            return 'Completed';
        }else if(in_array($chargeStatus,['NEW', 'PENDING'])){
            return 'Pending';
        }else if(in_array($chargeStatus,['EXPIRED', 'CANCELED', ' REFUND PENDING', 'REFUNDED'])){
            return 'Failed';
        }else{
            return $chargeStatus;
        }
    }

}
