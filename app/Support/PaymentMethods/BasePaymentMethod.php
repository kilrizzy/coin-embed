<?php

namespace App\Support\PaymentMethods;

class BasePaymentMethod
{

    protected $name;
    protected $label;
    protected $paymentLabel;
    protected $shortDescription;
    protected $types=[];

    public function getName(){
        return $this->name;
    }

    public function getTypes(){
        return $this->types;
    }

    public function getLabel(){
        return $this->label;
    }

    public function getShortDescription(){
        return $this->shortDescription;
    }

    public function getPaymentLabel(){
        if(empty($this->paymentLabel)){
            return $this->label;
        }
        return $this->paymentLabel;
    }

}
