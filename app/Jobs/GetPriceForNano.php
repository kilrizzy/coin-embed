<?php

namespace App\Jobs;

use App\Models\Price;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class GetPriceForNano implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apiKey = config('services.coinmarketcap.key');
        $response = Http::withHeaders(['X-CMC_PRO_API_KEY'=>$apiKey])
            ->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest',[
                'symbol'=>'BTC,NANO',
            ]);
        if(isset($response->object()->data->NANO)){
            $amountUSD = $response->object()->data->NANO->quote->USD->price;
            if($amountUSD > 0.001){
                $price = Price::findOrCreateByKey('nano');
                $price->updatePriceUSD($amountUSD);
            }
        }
        if(isset($amountUSD)){
            \Log::info('Complete! ('.$amountUSD.')');
        }else{
            \Log::info('Unable to get amount: '.json_encode($response->json()));
        }
    }
}
