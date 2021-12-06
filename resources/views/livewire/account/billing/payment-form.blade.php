<div>
    @if(!$complete)
    <div class="mb-2">
        <select wire:model="months" wire:change="changeMonths" class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
            @for($i=1;$i<=12;$i++)
            <option value="{{ $i }}">{{ $i }} Month Subscription</option>
            @endfor
        </select>
    </div>
    <div rel="coinembed-widget"
         data-ce-id="{{ config('app.billing_widget_id') }}"
         data-ce-frame-source-url="{{ url('/frame') }}"
         data-ce-api-url="{{ url('/api') }}"
         data-ce-amount="{{ $months * config('app.billing_monthly_price') }}"
         data-ce-currency="usd"
         data-ce-name="CoinEmbed"
         data-ce-image="https://images.unsplash.com/photo-1565373676943-403a8e5c2900?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=100&h=100&q=80"
         data-ce-callback="subscriptionPaymentSuccess"
    >Loading...</div>
    <script>
        function subscriptionPaymentSuccess(token){
            console.log('subscriptionPaymentSuccess',token);
            Livewire.emit('subscriptionPaymentSuccess', token);
        }
    </script>
    @else
        <div>
            Subscription updated!
        </div>
    @endif
</div>