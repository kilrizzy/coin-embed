<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'price_usd',
        'price_btc',
        'source',
        'last_updated_at',
    ];

    protected $dates = [
        'last_updated_at'
    ];

    public static function findByKey($key){
        return static::where('key',$key)->first();
    }

    public static function findOrCreateByKey($key){
        $model = static::findByKey($key);
        if(!$model){
            $model = static::create(['key'=>$key]);
        }
        return $model;
    }

    public function updatePriceUSD($amount){
        $this->price_usd = $amount;
        $this->last_updated_at = Carbon::now();
        $this->save();
    }

}
