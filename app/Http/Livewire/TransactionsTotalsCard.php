<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransactionsTotalsCard extends Component
{
    public function render()
    {
        $totalUSDAmount = 0;
        $totalAmounts = [];
        $totalAmounts['usd'] = [
            'amount' => 0,
            'amount_usd' => 0,
        ];
        $transactions = Auth::user()
            ->currentTeam
            ->transactions()
            ->with('blocks')
            ->whereNotNull('status')
            ->whereNotNull('payment_method_key')
            ->where('status','!=','New')
            ->withPayments()
            ->latest()
            ->get();
        $paymentsCount = $transactions->count();
        foreach($transactions as $transaction){
            $currency = $transaction->paidCurrency;
            if(!isset($totalAmounts[$currency])){
                $totalAmounts[$currency] = [
                    'amount' => 0,
                    'amount_usd' => 0,
                ];
            }
            $totalAmounts[$currency]['amount'] += $transaction->paidAmount;
            $paidUSDAmount = $transaction->paidUSDAmount;
            $totalAmounts[$currency]['amount_usd'] += $paidUSDAmount;
            $totalUSDAmount += $paidUSDAmount;
        }
        return view('livewire.transactions-totals-card',[
            'paymentsCount' => $paymentsCount,
            'totalUSDAmount' => $totalUSDAmount,
            'totalAmounts' => $totalAmounts,
        ]);
    }
}
