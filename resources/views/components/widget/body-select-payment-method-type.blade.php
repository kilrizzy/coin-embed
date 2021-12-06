@component('components.widget.body-container')
    <?php
    $paymentMethod = \App\Support\PaymentMethods\PaymentMethodRepository::findByName($transaction->payment_method_key);
    ?>
    @foreach($paymentMethod->getTypes() as $typeKey => $typeLabel)
        @if(in_array($typeKey,$widget->settings->payment_method_types))
            <div class="@if(!$loop->first) border-t border-gray-200 @endif">
                @include('components.widget.payment-method-type-button',[
                    'typeKey'=>$typeKey,
                    'typeLabel'=>$typeLabel,
                ])
            </div>
        @endif
    @endforeach
@endcomponent
@component('components.widget.footer-container')
    @slot('actionLeft')
        <button wire:click="returnToSelectPaymentMethod" class="cursor-pointer h-5 w-5 opacity-50 hover:opacity-100">
            @include('components.widget.back-icon')
        </button>
    @endslot
    Select a payment method type
@endcomponent
