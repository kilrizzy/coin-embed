<?php

namespace App\Models;

use App\Models\Casts\Json;
use App\Services\Nano\Nano;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PaymentMethodNanoAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method_id',
        'nano_address',
        'nano_index',
        'last_balance_check_at',
        'last_used_at',
        'last_consolidated_at',
        'data',
    ];

    protected $dates = [
        'last_balance_check_at',
        'last_used_at',
        'last_consolidated_at',
    ];

    protected $casts = [
        'data' => Json::class,
    ];

    public function getBalance()
    {
        $nano = new Nano();
        $address = $this->nano_address;
        \Log::info($address.' balance:');
        return Cache::remember($this->nano_address.'_balance', 5, function() use($nano,$address){
            $balance = $nano->call('account_balance',[
                'account' => $address,
            ]);
            return $balance;
        });
    }

}
