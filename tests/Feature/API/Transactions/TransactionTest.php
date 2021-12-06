<?php

namespace Tests\Feature\API\Transactions;

use App\Models\Block;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use BinaryCabin\NanoUnits\NanoUnits;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    use RefreshDatabase;

    public function testRequiresAuthentication()
    {
        $response = $this->get('/api/transaction/1');

        $response->assertStatus(302);
    }

    public function testOwnership()
    {
        $authUser = User::factory()->create();
        $authTeam = Team::factory()->state(['user_id'=>$authUser->id,'personal_team'=>true])->create();
        $differentUser = User::factory()->create();
        $differentTeam = Team::factory()->state(['user_id'=>$differentUser->id,'personal_team'=>true])->create();
        Sanctum::actingAs($authUser);
        $transaction = Transaction::factory()->state(['recipient_team_id'=>$differentTeam->id])->create();
        $response = $this->get('/api/transaction/'.$transaction->token);
        $response->assertStatus(401);
    }

    public function testShowTransaction()
    {
        $authUser = User::factory()->create();
        $authTeam = Team::factory()->state(['user_id'=>$authUser->id,'personal_team'=>true])->create();
        Sanctum::actingAs($authUser);
        $transaction = Transaction::factory()->state(['recipient_team_id'=>$authTeam->id])->create();
        $response = $this->get('/api/transaction/'.$transaction->token);
        $response->assertStatus(200);
    }

    public function testShowTransactionNano()
    {
        $authUser = User::factory()->create();
        $authTeam = Team::factory()->state(['user_id'=>$authUser->id,'personal_team'=>true])->create();
        Sanctum::actingAs($authUser);
        $transaction = Transaction::factory()->nano()->state(['recipient_team_id'=>$authTeam->id])->create();
        $blocks = Block::factory()->count(2)->state(['payment_method_key'=>'nano','transaction_id'=>$transaction->id])->create();
        $response = $this->get('/api/transaction/'.$transaction->token);
        $response->assertStatus(200);
        $responseJSON = json_decode($response->content());
        $this->assertEquals('nano',$responseJSON->currency);
        $this->assertEquals(2,count($responseJSON->blocks));
        $this->assertEquals($transaction->data->charge->amount_nano,$responseJSON->amount_expected);
        $amountReceivedRaw = 0;
        foreach($blocks as $block){
            $amountReceivedRaw += $block->data->amount ?? 0;
        }
        $amountReceived = NanoUnits::convert('raw','ticker',$amountReceivedRaw);
        $this->assertEquals($amountReceived,$responseJSON->amount_received);
        if($amountReceived > $transaction->data->charge->amount_nano){
            $this->assertEquals(true,$responseJSON->is_overpayment);
        }else{
            $this->assertEquals(false,$responseJSON->is_overpayment);
        }
        if($amountReceived < $transaction->data->charge->amount_nano){
            $this->assertEquals(true,$responseJSON->is_underpayment);
        }else{
            $this->assertEquals(false,$responseJSON->is_underpayment);
        }
    }

    public function testShowTransactionCoinbaseCommerce()
    {
        $authUser = User::factory()->create();
        $authTeam = Team::factory()->state(['user_id'=>$authUser->id,'personal_team'=>true])->create();
        Sanctum::actingAs($authUser);
        $transaction = Transaction::factory()->coinbaseCommerce('ethereum')->state(['recipient_team_id'=>$authTeam->id])->create();
        $response = $this->get('/api/transaction/'.$transaction->token);
        $response->assertStatus(200);
        $responseJSON = json_decode($response->content());
        $this->assertEquals('eth',$responseJSON->currency);
        $this->assertEquals(floatval("0.00225"),floatval($responseJSON->amount_expected));
        $this->assertEquals(floatval("0.00386417"),floatval($responseJSON->amount_received));
    }

    public function testShowTransactionStripe()
    {
        $authUser = User::factory()->create();
        $authTeam = Team::factory()->state(['user_id'=>$authUser->id,'personal_team'=>true])->create();
        Sanctum::actingAs($authUser);
        $transaction = Transaction::factory()->stripe()->state(['recipient_team_id'=>$authTeam->id])->create();
        $response = $this->get('/api/transaction/'.$transaction->token);
        $response->assertStatus(200);
        $responseJSON = json_decode($response->content());
        $this->assertEquals('usd',$responseJSON->currency);
        $this->assertEquals(number_format($transaction->amount_usd,2),number_format($responseJSON->amount_expected,2));
        $this->assertEquals('2.99',$responseJSON->amount_received);
    }

}
