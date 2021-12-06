<?php

namespace App\Models;

use App\Models\Casts\Json;
use App\Support\PaymentMethods\PaymentMethodRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BinaryCabin\LaravelUUID\Traits\HasUUID;

class Widget extends Model
{
    use HasFactory;

    use HasUUID;
    //use HasAttachments;
    //use BelongsToUser;

    protected $fillable = [
        'uuid',
        'user_id',
        'team_id',
        'name',
        'settings',
    ];

    protected $casts = [
        'settings' => Json::class,
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team(){
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function getPaymentMethodKeysAttribute(){
        $keys = [];
        if(!empty($this->paymentMethodTypes)){
            foreach($this->paymentMethodTypes as $paymentMethodType){
                $paymentMethodTypeParts = explode('.',$paymentMethodType);
                if(!empty($paymentMethodTypeParts) && !in_array($paymentMethodTypeParts[0],$keys)){
                    $keys[] = $paymentMethodTypeParts[0];
                }
            }
        }
        return $keys;
    }

    public function getPaymentMethodTypesAttribute(){
        if(isset($this->settings->payment_method_types)){
            return $this->settings->payment_method_types;
        }
        return [];
    }

    public function getPaymentMethodsAttribute(){
        $paymentMethods = collect();
        if(isset($this->payment_method_keys)){
            foreach($this->payment_method_keys as $paymentMethodKey){
                $paymentMethod = PaymentMethodRepository::findByName($paymentMethodKey);
                $paymentMethods->push($paymentMethod);
            }
        }
        return $paymentMethods;
    }

}
