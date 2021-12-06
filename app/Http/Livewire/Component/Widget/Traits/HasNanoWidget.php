<?php

namespace App\Http\Livewire\Component\Widget\Traits;

use App\Models\Price;

trait HasNanoWidget
{

    public function nanoInitiateCharge(){
        $paymentMethod = $this->widget->user->currentTeam->getPaymentMethod('nano');
        $charge = new \stdClass();
        try
        {
            $charge->address = $paymentMethod->getNextNanoAddress();
            $charge->amount_nano = $this->transaction->nanoCalculatePrice();
            $data = (object) $this->transaction->data;
            $data->charge = $charge;
            $transactionData = [
                'data' => $data,
            ];
            if(empty($this->transaction->status) || $this->transaction->status == 'New'){
                $transactionData['status'] = 'Pending';
            }
            $this->transaction->update($transactionData);
        }
        catch (\Symfony\Component\HttpKernel\Exception\HttpException $e)
        {
            $this->returnToSelectPaymentMethod();
            $this->emit('displayAlert', 'Error: '.$e->getMessage().'['.$e->getStatusCode().']');
            return null;
        }
    }

    public function getNanoSendAddressProperty(){
        return $this->transaction->data->charge->address->nano_address ?? null;
    }

    public function getNanoSendCurrencyAmountProperty(){
        return $this->transaction->data->charge->amount_nano ?? null;
    }

    public function getNanoSendCurrencyNameProperty(){
        return 'Nano';
    }

    public function getNanoAmountRawProperty(){
        return sprintf('%.0f',\BinaryCabin\NanoUnits\NanoUnits::convert('ticker','raw', $this->nanoSendCurrencyAmount ));
    }

    public function nanoCheckPayment(){
        if(isset($this->transaction->data->charge->address->nano_address)){
            $this->transaction->updateFromService();
            $this->transaction = $this->transaction->fresh();
            if($this->transaction->status == 'Completed' && empty($this->transaction->data->payment_marked_done)){
                $this->nanoMarkPaymentDone();
            }
        }
    }

    public function nanoMarkPaymentDone(){
        $transactionData = $this->transaction->data;
        $transactionData->payment_marked_done = true;
        $this->transaction->data = $transactionData;
        $this->transaction->save();
        $this->triggerPaymentComplete();
    }

}
