<?php

namespace App\Http\Livewire\Account\Widget;

use App\Models\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Stringable;
use Livewire\Component;

class Edit extends Component
{

    public $widget = null;
    public $widgetData = [];
    public $previewTransaction = [];

    public function mount(){
        $this->initiateWidgetData();
    }

    public function initiateWidgetData(){
        if(empty($this->widget)){
            $widget = new Widget();
            $this->widgetData = $widget->toArray();
            $this->widgetData['settings'] = [];
            $this->widgetData['settings']['payment_method_types'] = [];
        }else{
            $this->widgetData = json_decode(json_encode($this->widget->toArray()),true);
        }
    }

    public function render()
    {
        return view('livewire.account.widget.edit', ['widget'=>$this->widgetData]);
    }

    public function togglePaymentMethod($paymentMethodKey){
        if(in_array($paymentMethodKey, $this->widgetData['settings']['payment_method_types'])){
            if (($key = array_search($paymentMethodKey, $this->widgetData['settings']['payment_method_types'])) !== false) {
                unset($this->widgetData['settings']['payment_method_types'][$key]);
            }
        }else{
            $this->widgetData['settings']['payment_method_types'][] = $paymentMethodKey;
        }
    }

    public function save(){
        $widgetData = $this->widgetData;
        if(empty($widgetData['name'])){
            return;
        }
        if(empty($this->widget)){
            $widgetData['user_id'] = Auth::user()->id;
            $widgetData['team_id'] = Auth::user()->currentTeam->id;
            $widget = Widget::create($widgetData);
        }else{
            $widget = Auth::user()->currentTeam->widgets()->where('uuid', $this->widget->uuid)->first();
            $widget->update($widgetData);
        }
        session()->flash('success', 'Saved!');
        return redirect('/account/widgets');
    }

    public function delete(){
        $widget = Auth::user()->currentTeam->widgets()->where('uuid', $this->widget->uuid)->first();
        $widget->delete();
        session()->flash('success', 'Deleted!');
        return redirect('/account/widgets');
    }

    public function setPaymentMethod($paymentMethodKey){
        $this->previewTransaction['paymentMethodKey'] = $paymentMethodKey;
        $paymentMethodKeys = [];
        if(!empty($this->widgetData['settings']['payment_method_types'])){
            foreach($this->widgetData['settings']['payment_method_types'] as $paymentMethodTypeFull){
                $keyParts = explode('.',$paymentMethodTypeFull);
                if(!isset($paymentMethodKeys[$keyParts[0]]) ){
                    $paymentMethodKeys[$keyParts[0]] = [];
                }
                $paymentMethodKeys[$keyParts[0]][] = $keyParts[1];
            }
        }
        if(isset($paymentMethodKeys[$paymentMethodKey]) && count($paymentMethodKeys[$paymentMethodKey]) == 1){
            $this->previewTransaction['paymentMethodType'] = $paymentMethodKey.'.'.$paymentMethodKeys[$paymentMethodKey][0];
        }
    }

    public function setPaymentMethodType($type){
        $this->previewTransaction['paymentMethodType'] = $type;
    }

    public function returnToSelectPaymentMethod(){
        $this->previewTransaction['paymentMethodKey'] = null;
        $this->previewTransaction['paymentMethodType'] = null;
    }

}
