<?php

namespace App\Http\Livewire\Component\Widget;

use App\Http\Livewire\Component\Widget\Traits\HasCoinbaseCommerceWidget;
use App\Http\Livewire\Component\Widget\Traits\HasStripeWidget;
use App\Http\Livewire\Component\Widget\Traits\HasNanoWidget;
use App\Models\Transaction;
use App\Support\PaymentMethods\PaymentMethodRepository;
use Livewire\Component;

class Widget extends Component
{

    use HasStripeWidget;
    use HasCoinbaseCommerceWidget;
    use HasNanoWidget;

    public $widget = null;
    public $transaction = null;
    public $paymentData = [];
    public $paymentMethodKeyLabels = [];
    public $debug = true;

    public $displayAmount = null;
    public $displayProductName = 'Payment';
    public $displayProductImageURL = null;

    protected $listeners = ['stripeTokenCreated' => 'stripeTokenCreated'];

    public function mount(){
        $this->paymentMethodKeyLabels = $this->getPaymentMethodKeyLabels();
        if(request()->has('amount')){
            $this->displayAmount = request()->get('amount');
        }
        if(request()->has('productName')){
            $this->displayProductName = request()->get('productName');
        }
        if(request()->has('productImageUrl')){
            $this->displayProductImageURL = request()->get('productImageUrl');
        }
    }

    public function render()
    {
        return view('livewire.component.widget.widget');
    }

    public function setPaymentMethod($paymentMethodKey){
        $paymentMethod = PaymentMethodRepository::findByName($paymentMethodKey);
        $availablePaymentMethodTypes = [];
        foreach($paymentMethod->getTypes() as $typeKey => $typeValue){
            if(in_array($typeKey,$this->widget->settings->payment_method_types)){
                $availablePaymentMethodTypes[$typeKey] = $typeValue;
            }
        }
        if(count($availablePaymentMethodTypes) == 1){
            foreach($availablePaymentMethodTypes as $paymentMethodTypeKey => $paymentMethodTypeValue){
                $paymentMethodType = $paymentMethodTypeKey;
            }
        }
        $this->transaction = Transaction::create([
            'recipient_user_id' => $this->widget->user_id ?? null,
            'recipient_team_id' => $this->widget->team_id ?? null,
            'transactionable_id' => $this->widget->id ?? null,
            'transactionable_type' => \App\Models\Widget::class,
            'payment_method_key' => $paymentMethodKey,
            'payment_method_type' => $paymentMethodType ?? null,
            'amount' => $this->getAmountUSD(),
            'status' => 'New',
            'ip' => request()->ip(),
        ]);
        $this->emit('paymentMethodSet', $paymentMethodKey);
        $this->checkIfNewChargeNeeded();
    }

    public function setPaymentMethodType($paymentMethodType){
        $this->transaction->update([
            'payment_method_type' => $paymentMethodType,
        ]);
        $this->emit('paymentMethodTypeSet', $paymentMethodType);
        $this->checkIfNewChargeNeeded();
    }

    private function checkIfNewChargeNeeded(){
        if(!empty($this->transaction->payment_method_key) && !empty($this->transaction->payment_method_type)){
            if($this->transaction->payment_method_key == 'coinbase-commerce'){
                $this->coinbaseCommerceInitiateCharge();
            }
            if($this->transaction->payment_method_key == 'nano'){
                $this->nanoInitiateCharge();
            }
        }
    }

    protected function getDirectPaymentMethodType(){
        $paymentMethodKey = $this->transaction->payment_method_key ?? null;
        $paymentMethodType = $this->transaction->payment_method_type ?? null;
        if(!empty($paymentMethodType)){
            $paymentMethodType = str_replace($paymentMethodKey.'.', '', $paymentMethodType);
        }
        return $paymentMethodType;
    }

    protected function getAmountUSD(){
        $amount = preg_replace("/[^0-9.]/", "", $this->displayAmount);
        if(empty($amount)){
            $amount = 0;
        }
        return $amount;
    }

    protected function getAmountUSDCents(){
        $amount = preg_replace("/[^0-9.]/", "", $this->displayAmount);
        if(empty($amount)){
            $amount = 0;
        }
        $amount = $amount * 100;
        return intval($amount);
    }

    protected function triggerPaymentComplete(){
        $this->emit('transactionComplete', $this->transaction->token);
        $this->dispatchBrowserEvent('coinembed-success', ['token' => $this->transaction->token]);
    }

    public function returnToSelectPaymentMethod(){
        $this->transaction->update([
            'payment_method_key' => null,
            'payment_method_type' => null,
            'data' => null,
        ]);
    }

    public function returnToSelectPaymentType(){
        $this->transaction->update([
            'payment_method_type' => null,
            'data' => null,
        ]);
    }

    public function getPaymentMethodKeyLabels(){
        $paymentMethodKeys = [];
        if(isset($this->widget->settings->payment_method_types)){
            foreach($this->widget->settings->payment_method_types as $paymentMethodTypeFull){
                $keyParts = explode('.',$paymentMethodTypeFull);
                if(!isset($paymentMethodKeys[$keyParts[0]]) ){
                    $paymentMethodKeys[$keyParts[0]] = [];
                }
                $paymentMethodKeys[$keyParts[0]][] = $keyParts[1];
            }
        }
        return $paymentMethodKeys;
    }

}
