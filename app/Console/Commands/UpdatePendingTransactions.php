<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdatePendingTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:update-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Review abandoned or pending transactions to see if any payments have been made';

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
        $transactions = Transaction::whereIn('status',['Pending'])->where('created_at','>',Carbon::now()->subDay())->latest()->limit(25)->get();
        foreach($transactions as $transaction){
            $transaction->updateFromService();
        }
        return 0;
    }
}
