<?php

namespace App\Http\Livewire\Account\PaymentMethod;

use App\Models\PaymentMethod;
use App\Models\PaymentMethodNanoAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Nano extends Component
{

    protected $listeners = [
        'save' => 'save',
        'storeGeneratedAccounts' => 'storeGeneratedAccounts',
    ];

    protected $rules = [
        'consolidationAddress' => 'required|string|min:6',
    ];

    private $paymentMethodKey = 'nano';
    public $paymentMethod;
    public $consolidationAddress;

    public function save()
    {
        $paymentMethod = Auth::user()->currentTeam->getPaymentMethod($this->paymentMethodKey);
        $paymentMethod->setEncryptedEntry('consolidationAddress', $this->consolidationAddress);
        session()->flash('success', 'Saved!');
        return redirect('/account/payment-method');
    }

    public function mount()
    {
        $this->paymentMethod = Auth::user()->currentTeam->getPaymentMethod($this->paymentMethodKey);
        if($this->paymentMethod && $this->paymentMethod->getEncryptedEntry('consolidationAddress')){
            $this->consolidationAddress = $this->paymentMethod->getEncryptedEntry('consolidationAddress')->getValueDecrypted();
        }
    }

    public function render()
    {
        return view('livewire.account.payment-method.nano');
    }

    public function storeGeneratedAccounts($accounts){
        $paymentMethod = Auth::user()->currentTeam->getPaymentMethod($this->paymentMethodKey);
        if(!$paymentMethod){
            $paymentMethod = PaymentMethod::create([
                'user_id' => Auth::user()->id,
                'team_id' => Auth::user()->currentTeam->id,
                'payment_method_key' => $this->paymentMethodKey,
            ]);
        }
        foreach($accounts as $account){
            PaymentMethodNanoAddress::create([
                'payment_method_id' => $paymentMethod->id,
                'nano_address' => $account['address'],
                'nano_index' => $account['index'],
            ]);
        }
        $paymentMethod->data = ['accounts'=>$accounts];
        $paymentMethod->save();
        $this->paymentMethod = $paymentMethod->fresh();
    }

    public function deleteAccounts(){
        $paymentMethod = Auth::user()->currentTeam->getPaymentMethod($this->paymentMethodKey);
        PaymentMethodNanoAddress::where('payment_method_id', $paymentMethod->id)->delete();
        return redirect('account/payment-method/nano');
    }

}
