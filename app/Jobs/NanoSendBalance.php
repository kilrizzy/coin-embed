<?php

namespace App\Jobs;

use App\Services\Nano\Nano;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NanoSendBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $account;
    private $consolidationAddress;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account, $consolidationAddress)
    {
        $this->account = $account;
        $this->consolidationAddress = $consolidationAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $address = $this->account['address'];
        $secretKey = $this->account['secretKey'];
        $destinationAddress = $this->consolidationAddress;
        $nano = new Nano();
        \Log::info('NanoSendBalance');

        $accountInfoResponse = $nano->call('account_info', [
            'account' => $address,
        ]);
        \Log::info('account_info');
        \Log::info(json_encode($accountInfoResponse));

        $balanceResponse = $nano->call('account_balance',[
            'account' => $address,
        ]);
        \Log::info('account_balance');
        \Log::info(json_encode($balanceResponse));
        $amount = $balanceResponse->balance ?? 0;
        \Log::info('Send ' . $amount . ' To: ' . $destinationAddress);

        $accountHistory = $nano->call('account_history',[
            'account' => $address,
            'count' => 1,
        ]);
        \Log::info('account_history');
        \Log::info(json_encode($accountHistory));
        if(!empty($accountHistory->history[0])){
            $previous = $accountHistory->history[0]->hash;
        }else{
            $previous = $accountHistory->previous;
        }
        \Log::info('Previous');
        \Log::info(json_encode($previous));

        $representativeBlock = $accountInfoResponse->representative_block;
        \Log::info($representativeBlock);

        $representative = config('nano.representative');
        \Log::info($representative);

        $remainingBalance = 0; //Should always be 0 because sending full balance right?
        $remainingBalanceString = sprintf('%.0f', $remainingBalance);
        \Log::info($remainingBalanceString);

        $blockCreateData = [
            "json_block" => "true",
            'type' => 'state',
            'balance' => $remainingBalanceString,
            'key' => $secretKey,
            'representative' => $representative,
            'link' => $destinationAddress,
            'previous' => $previous,
        ];
        \Log::info(json_encode($blockCreateData));
        $blockCreateResponse = $nano->call('block_create', $blockCreateData);
        \Log::info('block_create');
        \Log::info(json_encode($blockCreateResponse));

        $processResponse = $nano->call('process', [
            "json_block" => "true",
            "subtype" => "send",
            'block' => $blockCreateResponse->block,
        ]);
        \Log::info('process');
        \Log::info(json_encode($processResponse));

    }
}
