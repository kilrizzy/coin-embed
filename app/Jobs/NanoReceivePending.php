<?php

namespace App\Jobs;

use App\Services\Nano\Nano;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NanoReceivePending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $account;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account)
    {
        $this->account = $account;
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
        $nano = new Nano();
        $response = $nano->call('pending',[
            'account' => $address,
        ]);
        \Log::info('NanoReceivePending');
        \Log::info(json_encode($response));
        if(!empty($response->blocks)){
            foreach($response->blocks as $block){
                \Log::info($address);
                \Log::info(json_encode($block));
                $blockInfoResponse = $nano->call('block_info',[
                    'json_block' => 'true',
                    'hash' => $block,
                ]);
                \Log::info(json_encode($blockInfoResponse));
                $accountInfoResponse = $nano->call('account_info', [
                    'account' => $address,
                ]);
                \Log::info(json_encode($accountInfoResponse));
                $createBlockData = [
                    'json_block' => 'true',
                    'type' => 'state',
                    'account' => $address,
                    'representative' => config('nano.representative'),
                    'balance' => $blockInfoResponse->amount,
                    'link' => $block,
                    'key' => $secretKey,
                ];
                if(isset($accountInfoResponse->error)){
                    $createBlockData['previous'] = '0';
                }else{
                    $accountHistory = $nano->call('account_history',[
                        'account' => $address,
                        'count' => 1,
                    ]);
                    \Log::info('account_history');
                    \Log::info(json_encode($accountHistory));
                    if(!empty($accountHistory->history[0])){
                        $createBlockData['previous'] = $accountHistory->history[0]->hash;
                    }else{
                        $createBlockData['previous'] = $accountHistory->previous;
                    }
                }
                \Log::info(json_encode($createBlockData));
                $blockCreateResponse = $nano->call('block_create', $createBlockData);
                \Log::info('block_create');
                \Log::info(json_encode($blockCreateResponse));
                $processResponse = $nano->call('process', [
                    "json_block" => "true",
                    'block' => $blockCreateResponse->block,
                ]);
                \Log::info('process');
                \Log::info(json_encode($processResponse));
                $receiveResponse = $nano->call('receive',[
                    'account' => $address,
                ]);
                \Log::info('receive');
                \Log::info(json_encode($receiveResponse));
            }
        }

    }
}
