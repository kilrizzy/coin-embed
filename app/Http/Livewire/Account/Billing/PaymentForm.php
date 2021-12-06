<?php

namespace App\Http\Livewire\Account\Billing;

use App\Models\SubscriptionPayment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PaymentForm extends Component
{

    public $months=12;
    public $complete=false;

    protected $listeners = ['subscriptionPaymentSuccess'];

    public function render()
    {
        return view('livewire.account.billing.payment-form');
    }

    public function changeMonths()
    {
        $this->dispatchBrowserEvent('coinembed-reinitiate', []);
    }

    public function subscriptionPaymentSuccess($token){
        $this->complete = true;
        $transaction = Transaction::where('token', $token)->first();
        if(!$transaction){
            abort(500, 'Transaction error');
        }
        $subscriptionPayment = SubscriptionPayment::create([
            'user_id' => Auth::user()->id,
            'team_id' => Auth::user()->currentTeam->id,
            'transaction_uuid' => $transaction->uuid,
            'months' => $this->months,
        ]);
        \Log::info('subscriptionPaymentSuccess: '.$token);
        \Log::info(json_encode($subscriptionPayment));
        //if(){
            //TODO - make a method on transaction->looksValid(). to account for Coinbase pendings
        //}
        //Increase subscription by one month
        $team = Auth::user()->currentTeam;
        $date = $team->subscription_ends_at;
        if(empty($date) || $date->lt(Carbon::now())){
            $date = Carbon::now();
        }
        $date->addMonths($this->months);
        $team->subscription_ends_at = $date;
        $team->save();
        $this->emit('subscriptionPaymentSaved');
        session()->flash('success', 'Payment successful!');
        return $this->redirect('/account/billing');

    }

}
