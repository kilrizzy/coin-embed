<?php

namespace App\Support\PaymentMethods;

class CoinbaseCommerce extends BasePaymentMethod
{

    protected $name = 'coinbase-commerce';
    protected $label = 'Coinbase Commerce';
    protected $paymentLabel = 'Cryptocurrency';
    protected $shortDescription = 'Accept BTC, LTC, ETH, and more';
    protected $types = [
        'coinbase-commerce.bitcoin'=>'Bitcoin',
        'coinbase-commerce.litecoin'=>'Litecoin',
        'coinbase-commerce.ethereum'=>'Ethereum',
        'coinbase-commerce.bitcoincash'=>'Bitcoin Cash',
        'coinbase-commerce.usdc'=>'USDC',
        'coinbase-commerce.dai'=>'Dai',
    ];

}
