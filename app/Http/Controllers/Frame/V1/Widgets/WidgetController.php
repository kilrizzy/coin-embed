<?php

namespace App\Http\Controllers\Frame\V1\Widgets;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function show(Request $request,$widgetUUID){
        $widget = Widget::findByUuid($widgetUUID);
        if(!$widget){
            abort(404, 'Widget Not Found');
        }
        return view('frame.v1.widget.show',['widget'=>$widget]);
    }
}
