<?php

namespace App\Support\PaymentMethods;

class Nano extends BasePaymentMethod
{

    protected $name = 'nano';
    protected $label = 'Nano';
    protected $shortDescription = 'Direct Nano integration';
    protected $types = ['nano.nano'=>'Nano'];

}
