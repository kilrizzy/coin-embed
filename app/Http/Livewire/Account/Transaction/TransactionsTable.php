<?php

namespace App\Http\Livewire\Account\Transaction;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionsTable extends Component
{

    use WithPagination;

    public $openTransactionDataModal = false;
    public $showOnlyTransactionsWithPayments = false;
    public $selectedModalTransaction;

    public function mount()
    {

    }

    public function render()
    {
        $transactionsQuery = Auth::user()
            ->currentTeam
            ->transactions()
            ->with('blocks')
            ->whereNotNull('status')
            ->whereNotNull('payment_method_key')
            ->where('status','!=','New');
        if($this->showOnlyTransactionsWithPayments){
            $transactionsQuery->withPayments();
        }
        $transactionsQuery->latest();
        $transactions = $transactionsQuery->paginate(10);
        return view('livewire.account.transaction.transactions-table', [
            'transactions' => $transactions
        ]);
    }

    public function openTransactionDataModal($transaction){
        $this->selectedModalTransaction = $transaction;
        $this->openTransactionDataModal = true;
    }

    public function getModalTransactionProperty()
    {
        return $this->selectedModalTransaction;
    }
}
