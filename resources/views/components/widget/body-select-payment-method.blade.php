@component('components.widget.body-container')
    @if(empty($paymentData['type']))
        @if($widget->payment_methods->count() == 1)
            @foreach($widget->payment_methods as $paymentMethod)
                <div class="@if(!$loop->first) border-t border-gray-200 @endif">
                    @include('components.widget.payment-method-single-button',['paymentMethod'=>$paymentMethod])
                </div>
            @endforeach
        @endif
        @if($widget->payment_methods->count() > 1)
            @foreach($widget->payment_methods as $paymentMethod)
                <div class="@if(!$loop->first) border-t border-gray-200 @endif">
                    @include('components.widget.payment-method-button',['paymentMethod'=>$paymentMethod])
                </div>
            @endforeach
        @endif
    @endif
@endcomponent
@component('components.widget.footer-container')
    Select a payment method
@endcomponent