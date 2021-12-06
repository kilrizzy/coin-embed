<div>
    @if(!$complete)
        <div rel="coinembed-widget"
             data-ce-id="{{ config('app.donation_widget_id') }}"
             data-ce-frame-source-url="{{ url('/frame') }}"
             data-ce-api-url="{{ url('/api') }}"
             data-ce-amount="{{ $price }}"
             data-ce-currency="usd"
             data-ce-name="Send us a tip!"
             data-ce-image="https://images.unsplash.com/photo-1571172964533-d2d13d88ce7e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=100&h=100&q=80"
             data-ce-callback="donationPaymentSuccess"
        >Loading...</div>
        <script src="/v1/coinembed.js"></script>
        <script>
            function donationPaymentSuccess(token){
                console.log('donationPaymentSuccess',token);
                Livewire.emit('donationPaymentSuccess', token);
            }
        </script>
    @else
        <div>
            Thanks for the support! 😍
        </div>
    @endif
</div>