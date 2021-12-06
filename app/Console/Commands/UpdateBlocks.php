<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBlocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blocks:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update blocks for nano';

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
        //Nano
        $transactions = Transaction::whereIn('status',['Pending','Completed'])
            ->where('payment_method_key','nano')
            ->where('created_at','>',Carbon::now()->subHours(4))->latest()->limit(100)->get();
        foreach($transactions as $transaction){
            $transaction->updateFromService();
            $this->info('UpdateBlocks For Transaction: '.$transaction->id);
            \Log::info('UpdateBlocks For Transaction: '.$transaction->id);
        }
        return 0;
    }
}
