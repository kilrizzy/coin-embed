<?php

namespace App\Models;

use App\Models\Casts\Json;
use App\Models\EncryptedEntry;
use App\Models\User;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{

    use HasUUID;

    protected $fillable = [
        'uuid',
        'user_id',
        'team_id',
        'payment_method_key',
        'data',
    ];

    protected $casts = [
        'data' => Json::class,
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function encryptedEntries(){
        return $this->morphMany(EncryptedEntry::class,'encryptable');
    }

    public function getEncryptedEntry($key){
        return $this->encryptedEntries()->where('key',$key)->first();
    }

    public function setEncryptedEntry($key, $value){
        $encryptedEntry = $this->getEncryptedEntry($key);
        if($encryptedEntry){
            $encryptedEntry->value = encrypt($value);
            $encryptedEntry->save();
        }else{
            $encryptedEntry = EncryptedEntry::create([
                'encryptable_id' => $this->id,
                'encryptable_type' => get_class($this),
                'key' => $key,
                'value' => encrypt($value),
            ]);
        }
        return $encryptedEntry;
    }

    public function nanoAddresses(){
        return $this->hasMany(PaymentMethodNanoAddress::class);
    }

    public function getNextNanoAddress(){
        $address = $this->nanoAddresses()->whereNull('last_used_at')->orderBy('id','ASC')->first();
        if(!$address){
            $address = $this->nanoAddresses()->orderBy('last_used_at','ASC')->orderBy('id','ASC')->first();
        }
        if(!$address){
            abort('404', 'Unable to find address');
        }
        $address->last_used_at = Carbon::now();
        $address->save();
        $balances = $address->getBalance();
        if(!isset($balances->balance) || !isset($balances->pending)){
            abort('404', 'Unable to get balance for address');
        }
        if($balances->balance != 0 || $balances->pending != 0){
            abort('404', 'Unable to find address with no balance. Please contact the account owner');
        }
        return $address;
    }

}
