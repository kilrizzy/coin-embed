<?php

namespace App\Support\PaymentMethods;

class Stripe extends BasePaymentMethod
{

    protected $name = 'stripe';
    protected $label = 'Stripe';
    protected $paymentLabel= 'Credit Card';
    protected $shortDescription = 'Accept Credit Cards';
    protected $types = ['stripe.credit-card'=>'Credit Card'];

}
