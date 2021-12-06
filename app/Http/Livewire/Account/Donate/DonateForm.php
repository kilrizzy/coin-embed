<?php

namespace App\Http\Livewire\Account\Donate;

use Livewire\Component;

class DonateForm extends Component
{

    public $price="0.99";
    public $complete=false;

    protected $listeners = ['donationPaymentSuccess'];

    public function render()
    {
        return view('livewire.account.donate.donate-form');
    }

    public function donationPaymentSuccess($token){
        $this->complete = true;
        \Log::info('donationPaymentSuccess: '.$token);
    }

}
