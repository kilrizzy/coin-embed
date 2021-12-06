<?php

namespace App\Http\Controllers\Account\PaymentMethods;

use App\Http\Controllers\Controller;
use App\Support\PaymentMethods\PaymentMethodRepository;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(){
        return view('account.payment-method.index');
    }

    public function show($paymentMethodKey){
        $paymentMethod = PaymentMethodRepository::findByName($paymentMethodKey);
        if(!$paymentMethod){
            abort(404, 'Payment Method Not Found');
        }
        // TODO - rm after nano complete
        if($paymentMethod->getName() == 'nano' && empty(config('app.nano_enabled'))){
            return redirect('/account/payment-method')->withError('Nano support coming soon!');
        }
        return view('account.payment-method.show',[
            'paymentMethod' => $paymentMethod,
        ]);
    }

}
