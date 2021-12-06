@component('components.widget.widget-container')
    @include('components.widget.widget-header',[
        'displayProductImageURL' => $displayProductImageURL,
        'displayProductName' => $displayProductName,
        'displayAmount' => $displayAmount,
    ])
        <div class="bg-white">
            @if(!isset($transaction->payment_method_key))
                @include('components.widget.body-select-payment-method',[
                    'paymentMethodKeys' => $widget->settings->payment_method_types ?? []
                ])
            @endif
            @if(!empty($transaction) && !empty($transaction->payment_method_key))
                @if(empty($transaction->payment_method_type))
                    @include('components.widget.body-select-payment-method-type')
                @else
                    @if($transaction->payment_method_key == 'stripe')
                        @include('components.widget.body-pay-stripe')
                    @elseif($transaction->payment_method_key == 'coinbase-commerce')
                        @include('components.widget.body-pay-coinbase-commerce')
                    @else
                        @include('components.widget.body-pay-nano')
                    @endif
                @endif
            @endif
        </div>
        @include('components.widget.sponsor')
@endcomponent

@section('scripts')
<script>
    Livewire.on('displayAlert', message => {
        console.log(message);
        alert(message);
    });
</script>
@endsection
