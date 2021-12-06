<?php

namespace App\Http\Livewire\Account\PaymentMethod\Nano;

use App\Jobs\NanoReceivePending;
use App\Jobs\NanoSendBalance;
use App\Services\Nano\Nano;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Consolidator extends Component
{

    public $consolidationAddress;
    //public $currentQueueItem = [];
    public $addressValues = [];

    protected $listeners = [
        'nanoCheckPaymentMethodBalances',
        'nanoSetAddressValues',
        'receivePendingForAccount',
        'sendBalanceForAccount',
        'nanoSetCurrentQueueItem',
    ];

    public function render()
    {
        return view('livewire.account.payment-method.nano.consolidator');
    }

    public function getFirstExpectedAddressProperty()
    {
        $paymentMethod = Auth::user()->currentTeam->getPaymentMethod('nano');
        $accounts = $paymentMethod->data->accounts;
        return $accounts[0]->address ?? null;
    }

    public function nanoCheckPaymentMethodBalances($addresses){
        $nano = new Nano();
        $accountsBalancesResponse = $nano->call('accounts_balances',['accounts'=>$addresses]);
        $this->emit('accountsBalancesReceived', $accountsBalancesResponse);
        return $accountsBalancesResponse;
    }

    public function receivePendingForAccount($account){
        NanoReceivePending::dispatch($account);
        $this->emit('nanoPendingReceived', $account['address']);
    }

    public function sendBalanceForAccount($account){
        NanoSendBalance::dispatch($account, $this->consolidationAddress);
        $this->emit('nanoBalanceSent', $account['address']);
    }

    /*public function nanoSetCurrentQueueItem($currentQueueItem){
        $this->currentQueueItem = $currentQueueItem;
    }

    public function checkBalancesIfCurrentQueueItem(){
        \Log::info('checkBalancesIfCurrentQueueItem');
        if(empty($this->currentQueueItem)){
            \Log::info('empty');
            return null;
        }
        \Log::info(json_encode($this->currentQueueItem));
        $this->nanoCheckPaymentMethodBalances($this->addressValues);
    }*/

    public function nanoSetAddressValues($addressValues){
        $this->addressValues = $addressValues;
    }

}
