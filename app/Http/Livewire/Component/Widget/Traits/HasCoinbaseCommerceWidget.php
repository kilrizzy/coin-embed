<?php

namespace App\Http\Livewire\Component\Widget\Traits;

use App\Support\Services\CoinbaseCommerce\CoinbaseCommerce;

trait HasCoinbaseCommerceWidget
{

    public function coinbaseCommerceInitiateCharge(){
        $charge = null;
        $coinbaseCommerceService = new CoinbaseCommerce();
        $paymentMethod = $this->widget->user->currentTeam->getPaymentMethod('coinbase-commerce');
        $privateKeyEncryptedEntry = $paymentMethod->getEncryptedEntry('apiKeyPrivate');
        $privateKey = $privateKeyEncryptedEntry->getValueDecrypted();
        $coinbaseCommerceService->setCredentials(['apiKeyPrivate'=>$privateKey]);
        $charge = $coinbaseCommerceService->createCharge([
            'pricing_type' => 'fixed_price',
            'local_price' => [
                'amount' => $this->getAmountUSD(),
                'currency' => 'USD',
            ],
            'name' => $this->widget->name.': '.$this->displayProductName,
            'description' => $this->transaction->uuid,
            'metadata' => [
                'widget_uuid' => $this->widget->uuid,
                'transaction_uuid' => $this->transaction->uuid,
            ]
        ]);
        $data = (object) $this->transaction->data;
        $data->charge = $charge;
        $transactionData = [
            'data' => $data,
        ];
        if(isset($data->charge->data->timeline)){
            $lastTimeline = end($transactionData['data']->charge->data->timeline);
            $transactionData['status'] = CoinbaseCommerce::getStatusFromChargeStatus($lastTimeline->status);
        }
        $this->transaction->update($transactionData);
        if(isset($this->transaction->data->payment_marked_done) && !empty($this->transaction->data->payment_marked_done)){
            $this->triggerPaymentComplete();
        }
    }

    public function getCoinbaseCommerceSendAddressProperty(){
        $paymentMethodType = $this->transaction->payment_method_direct_type;
        if(!empty($paymentMethodType) && isset($this->transaction->data->charge->data->addresses->{$paymentMethodType})){
            return $this->transaction->data->charge->data->addresses->{$paymentMethodType};
        }
        return null;
    }

    public function getCoinbaseCommerceSendCurrencyAmountProperty(){
        $paymentMethodType = $this->transaction->payment_method_direct_type;
        if(!empty($paymentMethodType) && isset($this->transaction->data->charge->data->pricing->{$paymentMethodType}->amount)){
            return $this->transaction->data->charge->data->pricing->{$paymentMethodType}->amount;
        }
        return null;
    }

    public function getCoinbaseCommerceSendCurrencyNameProperty(){
        $paymentMethodType = $this->transaction->payment_method_direct_type;
        if(!empty($paymentMethodType) && isset($this->transaction->data->charge->data->pricing->{$paymentMethodType}->currency)){
            return $this->transaction->data->charge->data->pricing->{$paymentMethodType}->currency;
        }
        return null;
    }

    public function coinbaseCommerceCheckPayment(){
        if(isset($this->transaction->data->charge->data->id)){
            $this->transaction->updateFromService();
            $this->transaction = $this->transaction->fresh();
            if($this->transaction->status == 'Completed' && empty($this->transaction->data->payment_marked_done)){
                $this->coinbaseCommerceMarkPaymentDone();
            }
        }
    }

    public function coinbaseCommerceMarkPaymentDone(){
        $transactionData = $this->transaction->data;
        $transactionData->payment_marked_done = true;
        $this->transaction->data = $transactionData;
        $this->transaction->save();
        $this->triggerPaymentComplete();
    }

}
