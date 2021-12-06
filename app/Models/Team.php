<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{

    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    protected $dates = [
        'subscription_ends_at',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function widgets(){
        return $this->hasMany(Widget::class,'team_id');
    }

    public function hasPaymentMethod($paymentMethodType){
        if($this->getPaymentMethod($paymentMethodType)){
            return true;
        }
        return false;
    }

    public function paymentMethods(){
        return $this->hasMany(PaymentMethod::class, 'team_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'recipient_team_id');
    }

    public function getPaymentMethod($paymentMethodKey){
        return $this->paymentMethods()->where('payment_method_key', $paymentMethodKey)->first();
    }

    public function getSubscriptionIsActiveAttribute(){
        if(empty($this->subscription_ends_at)){
            return false;
        }
        return $this->subscription_ends_at->gt(Carbon::now());
    }

}
