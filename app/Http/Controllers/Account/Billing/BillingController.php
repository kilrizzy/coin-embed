<?php

namespace App\Http\Controllers\Account\Billing;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Support\PaymentMethods\PaymentMethodRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index(){
        $subscriptionPayments = SubscriptionPayment::where('team_id', Auth::user()->currentTeam->id)->latest()->paginate(10);
        return view('account.billing.index',[
            'subscriptionPayments' => $subscriptionPayments
        ]);
    }
}
