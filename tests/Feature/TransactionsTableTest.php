<?php

namespace Tests\Feature;

use App\Http\Livewire\Account\Transaction\TransactionsTable;
use App\Models\Block;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Widget;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TransactionsTableTest extends TestCase
{

    use RefreshDatabase;

    public function testListOwnedTransactions()
    {
        $authUser = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($authUser);
        $authTeam = Team::factory()->state([
            'user_id'=>$authUser->id,
            'personal_team'=>true
        ])->create();
        $otherTeam = Team::factory()->state([
            'user_id'=>$otherUser->id,
            'personal_team'=>true
        ])->create();
        $widget = Widget::factory()->state([
            'team_id'=>$authTeam->id,
        ])->create();
        $otherWidget = Widget::factory()->state([
            'team_id'=>$otherTeam->id,
        ])->create();
        $transactions = Transaction::factory()->count(3)->state([
            'recipient_team_id'=>$authTeam->id,
            'transactionable_id' => $widget->id,
            'transactionable_type' => get_class($widget),
            'status'=>'Pending'
        ])->create();
        $unownedTransactions = Transaction::factory()->count(3)->state([
            'recipient_team_id'=>$otherTeam->id,
            'transactionable_id' => $otherWidget->id,
            'transactionable_type' => get_class($otherWidget),
            'status'=>'Pending'
        ])->create();
        foreach($transactions as $transaction){
            Livewire::test(TransactionsTable::class)
                ->assertSee($transaction->uuid);
        }
        foreach($unownedTransactions as $transaction){
            Livewire::test(TransactionsTable::class)
                ->assertDontSee($transaction->uuid);
        }
    }

    public function testListOwnedTransactionsWithBalance()
    {
        $authUser = User::factory()->create();
        $this->actingAs($authUser);
        $authTeam = Team::factory()->state([
            'user_id'=>$authUser->id,
            'personal_team'=>true
        ])->create();
        $widget = Widget::factory()->state([
            'team_id'=>$authTeam->id,
        ])->create();
        $transactions = Transaction::factory()->count(3)->state([
            'recipient_team_id'=>$authTeam->id,
            'transactionable_id' => $widget->id,
            'transactionable_type' => get_class($widget),
            'status'=>'Pending'
        ])->create();
        $transactionsWithPaymentsNano = Transaction::factory()->nano()->count(2)->state([
            'recipient_team_id'=>$authTeam->id,
            'transactionable_id' => $widget->id,
            'transactionable_type' => get_class($widget),
            'status'=>'Pending'
        ])->create();
        $transactionsWithPaymentsCoinbaseCommerce = Transaction::factory()->coinbaseCommerce()->count(2)->state([
            'recipient_team_id'=>$authTeam->id,
            'transactionable_id' => $widget->id,
            'transactionable_type' => get_class($widget),
            'status'=>'Pending'
        ])->create();
        $transactionsWithPaymentsStripe = Transaction::factory()->stripe()->count(2)->state([
            'recipient_team_id'=>$authTeam->id,
            'transactionable_id' => $widget->id,
            'transactionable_type' => get_class($widget),
            'status'=>'Pending'
        ])->create();
        foreach($transactionsWithPaymentsNano as $transactionsWithPayment){
            Block::factory()->state([
                'block_id' => '11111',
                'payment_method_key' => 'nano',
                'transaction_id' => $transactionsWithPayment->id,
                'status' => null,
                'data' => null,
                'completed_at' => Carbon::now()->subDay(),
            ])->create();
        }
        foreach($transactions as $transaction){
            Livewire::test(TransactionsTable::class)
                ->set('showOnlyTransactionsWithPayments', true)
                ->assertDontSee($transaction->uuid);
        }
        foreach($transactionsWithPaymentsNano as $transaction){
            Livewire::test(TransactionsTable::class)
                ->set('showOnlyTransactionsWithPayments', true)
                ->assertSee($transaction->uuid);
        }
        foreach($transactionsWithPaymentsCoinbaseCommerce as $transaction){
            Livewire::test(TransactionsTable::class)
                ->set('showOnlyTransactionsWithPayments', true)
                ->assertSee($transaction->uuid);
        }
        foreach($transactionsWithPaymentsStripe as $transaction){
            Livewire::test(TransactionsTable::class)
                ->set('showOnlyTransactionsWithPayments', true)
                ->assertSee($transaction->uuid);
        }
    }

}
