<?php

namespace App\Support\PaymentMethods;

class PaymentMethodRepository
{

    public static function all()
    {
        return [
            new CoinbaseCommerce(),
            new Nano(),
            new Stripe(),
        ];
    }

    public static function findByName($name)
    {
        foreach(static::all() as $paymentMethod){
            if($paymentMethod->getName() == $name){
                return $paymentMethod;
            }
        }
        return null;
    }

}