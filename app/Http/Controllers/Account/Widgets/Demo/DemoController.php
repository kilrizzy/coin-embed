<?php

namespace App\Http\Controllers\Account\Widgets\Demo;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Support\PaymentMethods\PaymentMethodRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{

    public function show($widgetKey){
        $widget = Auth::user()->currentTeam->widgets()->where('uuid',$widgetKey)->first();
        if(!$widget){
            abort(404, 'Widget Not Found');
        }
        return view('account.widget.demo.show',[
            'widget' => $widget,
        ]);
    }

}
