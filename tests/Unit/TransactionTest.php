<?php

namespace Tests\Unit;

use App\Mail\TransactionComplete;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Widget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    use RefreshDatabase;

    public function testSendWebhookOnTransactionComplete()
    {
        Http::fake();
        $webhookURL = 'http://example.com/transaction/complete';
        $authUser = User::factory()->create();
        $authTeam = Team::factory()->state([
            'user_id'=>$authUser->id,
            'personal_team'=>true
        ])->create();
        $widgetSettings = [];
        $widgetSettings['webhook_url'] = $webhookURL;
        $widget = Widget::factory()->state([
            'team_id'=>$authTeam->id,
            'settings' => $widgetSettings
        ])->create();
        $transaction = Transaction::factory()->state([
            'recipient_team_id'=>$authTeam->id,
            'transactionable_id' => $widget->id,
            'transactionable_type' => get_class($widget),
            'status'=>'Pending'
        ])->create();
        $transaction->setStatusCompleted();
        Http::assertSent(function ($request) use($webhookURL) {
            return $request->url() == $webhookURL;
        });
        Http::assertSent(function ($request) {
            return $request['action'] == 'transaction-completed';
        });
        Http::assertSent(function ($request) use($transaction) {
            $requestTransaction = (object) $request['transaction'];
            return $requestTransaction->uuid == $transaction->uuid;
        });
    }

    public function testSendEmailOnTransactionComplete()
    {
        $this->withoutExceptionHandling();
        Mail::fake();
        $authUser = User::factory()->create();
        $authTeam = Team::factory()->state([
            'user_id'=>$authUser->id,
            'personal_team'=>true
        ])->create();
        $widget = Widget::factory()->state([
            'team_id'=>$authTeam->id,
        ])->create();
        $transaction = Transaction::factory()->state([
            'recipient_team_id'=>$authTeam->id,
            'transactionable_id' => $widget->id,
            'transactionable_type' => get_class($widget),
            'status'=>'Pending'
        ])->create();
        $transaction->setStatusCompleted();
        Mail::assertQueued(TransactionComplete::class, function ($mail) use ($transaction) {
            return $mail->hasTo($transaction->recipientTeam->owner->email);
        });
    }

}
