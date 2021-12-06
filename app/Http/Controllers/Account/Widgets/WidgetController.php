<?php

namespace App\Http\Controllers\Account\Widgets;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Support\PaymentMethods\PaymentMethodRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WidgetController extends Controller
{
    public function index(){
        if(Auth::user()->currentTeam->paymentMethods->count() == 0){
            return redirect('/account/payment-method')->withError('To create a widget, first add a payment method');
        }
        return view('account.widget.index');
    }

    public function create(){
        return view('account.widget.create');
    }

    public function show($widgetKey){
        $widget = Auth::user()->currentTeam->widgets()->where('uuid',$widgetKey)->first();
        if(!$widget){
            abort(404, 'Widget Not Found');
        }
        return view('account.widget.show',[
            'widget' => $widget,
        ]);
    }

}
