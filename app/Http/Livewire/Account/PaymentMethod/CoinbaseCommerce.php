<?php

namespace App\Http\Livewire\Account\PaymentMethod;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CoinbaseCommerce extends Component
{

    protected $listeners = [
        'save' => 'save'
    ];

    protected $rules = [
        'apiKeyPublic' => 'required|string|min:6',
        'apiKeyPrivate' => 'required|string|max:500',
    ];

    private $paymentMethodKey = 'coinbase-commerce';

    public $apiKeyPrivate = null;
    public $serviceConnection = false;
    public $serviceConnectionError = null;

    public function save()
    {
        $paymentMethod = Auth::user()->currentTeam->getPaymentMethod($this->paymentMethodKey);
        if(!$paymentMethod){
            $paymentMethod = PaymentMethod::create([
                'user_id' => Auth::user()->id,
                'team_id' => Auth::user()->currentTeam->id,
                'payment_method_key' => $this->paymentMethodKey,
            ]);
        }
        $paymentMethod->setEncryptedEntry('apiKeyPrivate', $this->apiKeyPrivate);
        session()->flash('success', 'Saved!');
        return redirect('/account/payment-method');
    }

    public function mount()
    {
        $paymentMethod = Auth::user()->currentTeam->getPaymentMethod($this->paymentMethodKey);
        if($paymentMethod){
            $this->apiKeyPrivate = $paymentMethod->getEncryptedEntry('apiKeyPrivate')->getValueDecrypted();
            if(!empty($this->apiKeyPrivate)){
                $service = new \App\Support\Services\CoinbaseCommerce\CoinbaseCommerce();
                $service->setCredentials([
                    'apiKeyPrivate' => $this->apiKeyPrivate
                ]);
                $charges = $service->getCharges();
                if(isset($charges->error)){
                    $this->serviceConnection = false;
                    $this->serviceConnectionError = $charges->error->message;
                }else{
                    $this->serviceConnection = false;
                    $this->serviceConnectionError = null;
                    if(isset($charges->data)){
                        $this->serviceConnection = true;
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.account.payment-method.coinbase-commerce');
    }
}
