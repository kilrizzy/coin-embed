<?php

namespace App\Console\Commands;

use App\Services\Nano\Nano;
use Illuminate\Console\Command;

class NanoCheckAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nano:address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check payments on an address';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nano = new Nano();
        $address = 'nano_14yi949jig9mbr5aqcmy661apag6ni5axhw4zht44qxi4kt99e79znrqdpoz';

        $accountInfoResponse = $nano->call('account_info',['account'=>$address]);
        if(isset($accountInfoResponse->error) && !empty($accountInfoResponse->error)){
            $this->error(json_encode($accountInfoResponse->error));
            return 0;
        }
        dump($accountInfoResponse);

        // Find the latest charge that has used
        $accountHistoryResponse = $nano->call('account_history',['account'=>$address,'count'=>'-1']);
        dump($accountHistoryResponse);
        if(isset($accountHistoryResponse->history)){
            dump($accountHistoryResponse->history);
            foreach($accountHistoryResponse->history as $historyBlock){
                //Check if this block exists, add if not
                dump($historyBlock);
            }
        }
        /*if(isset($accountInfoResponse->error) && !empty($accountInfoResponse->error)){
            $this->error(json_encode($accountInfoResponse->error));
            return 0;
        }
        dump($accountInfoResponse);*/

        /*
         * {#1168
  +"frontier": "89AC7778CC7324B3F142BC699A60662B947480E35968704B464C5C025858C804"
  +"open_block": "89AC7778CC7324B3F142BC699A60662B947480E35968704B464C5C025858C804"
  +"representative_block": "89AC7778CC7324B3F142BC699A60662B947480E35968704B464C5C025858C804"
  +"balance": "1000000000000000000000000"
  +"modified_timestamp": "1605451657"
  +"block_count": "1"
  +"account_version": "2"
  +"confirmation_height": "1"
  +"confirmation_height_frontier": "89AC7778CC7324B3F142BC699A60662B947480E35968704B464C5C025858C804"
}
         */
        return 0;
    }
}
