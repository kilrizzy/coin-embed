<?php

namespace App\Console\Commands;

use App\Models\Price;
use Illuminate\Console\Command;

class GetPriceForNano extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:nano';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get and store current price for Nano';

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
        \App\Jobs\GetPriceForNano::dispatchNow();
        $price = Price::findOrCreateByKey('nano');
        \Log::info(json_encode($price));
        return 0;
    }
}
