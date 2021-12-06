<?php

namespace App\Http\Livewire\Component\Widget\Traits;

use App\Support\Services\Stripe\Stripe;

trait HasStripeWidget
{

    public function stripeTokenCreated($token){
        $data = (object) $this->transaction->data;
        $data->token = $token;
        $this->transaction->update([
            'data' => $data,
        ]);
        $this->stripeTokenAttemptCharge();
    }

    public function stripeTokenAttemptCharge(){
        if(isset($this->transaction->data->token['id'])){
            $tokenId = $this->transaction->data->token['id'];
            $stripeService = new Stripe();
            $stripePaymentMethod = $this->widget->user->currentTeam->getPaymentMethod('stripe');
            $stripePrivateKeyEncryptedEntry = $stripePaymentMethod->getEncryptedEntry('apiKeyPrivate');
            $stripePrivateKey = $stripePrivateKeyEncryptedEntry->getValueDecrypted();
            $stripeService->setCredentials(['apiKeyPrivate'=>$stripePrivateKey]);
            $charge = $stripeService->createCharge([
                'amount' => $this->getAmountUSDCents(),
                'currency' => 'usd',
                'source' => $tokenId,
                'description' => $this->widget->name.': '.$this->displayProductName,
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
            if(isset($charge->status)){
                if($charge->status == 'succeeded'){
                    $transactionData['status'] = 'Completed';
                    $this->transaction->setStatusCompleted();
                }else if($charge->status == 'pending'){
                    $transactionData['status'] = 'Pending';
                }else if($charge->status == 'failed'){
                    $transactionData['status'] = 'Failed';
                }else{
                    $transactionData['status'] = $charge->status;
                }
            }
            $this->transaction->update($transactionData);
            if(isset($this->transaction->data->charge->id)){
                $this->triggerPaymentComplete();
            }
            return $charge;
        }
        return null;
    }

}
