<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div id="app">
    @yield('content')
</div>

@livewireScripts
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
<script>
    Livewire.on('paymentMethodSet', paymentMethodKey => {
        if(paymentMethodKey == 'stripe'){
            if(!stripeCardNumber){
                window.stripeInitiate(true);
            }else{
                //Indicate this is a re-initiation, otherwise will hit "Can only create one Element of type cardNumber"
                window.stripeInitiate(false);
            }
        }
    })
    Livewire.on('transactionComplete', transactionToken => {
        parent.postMessage({
            CoinEmbed: {
                id: "{{ request()->get('instanceId') }}",
                token: transactionToken
            }
        }, '*');
    })
</script>

<?php
if(isset($widget) && isset($widget->team)){
    $stripePaymentMethod = $widget->team->getPaymentMethod('stripe');
    if($stripePaymentMethod){
        $stripePublicKeyEncryptedEntry = $stripePaymentMethod->getEncryptedEntry('apiKeyPublic');
        $stripePublicKey = $stripePublicKeyEncryptedEntry->getValueDecrypted();
    }
}
?>
<script>

    let stripe;
    let stripeElements;
    let stripeCardNumber;
    let stripeCardExpiration;
    let stripeCardCVC;
    stripeMount();

    function stripeMount(){
        stripe = Stripe('{{ $stripePublicKey ?? null }}');
        stripeElements = stripe.elements({});
        stripeCardNumber = null;
        stripeCardExpiration = null;
        stripeCardCVC = null;
    }

    function stripeInitiate(initial=true){
        if(initial){
            let elementStyles = {
                base: {
                    color: '#32325D',
                    fontWeight: 500,
                    fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
                    fontSize: '16px',
                    fontSmoothing: 'antialiased',

                    '::placeholder': {
                        color: '#CFD7DF',
                    },
                    ':-webkit-autofill': {
                        color: '#e39f48',
                    },
                },
                invalid: {
                    color: '#E25950',
                    '::placeholder': {
                        color: '#FFCCA5',
                    },
                },
            };

            let elementClasses = {
                focus: 'focused',
                empty: 'empty',
                invalid: 'invalid',
            };

            stripeCardNumber = stripeElements.create('cardNumber', {
                style: elementStyles,
                classes: elementClasses,
            });

            stripeCardExpiration = stripeElements.create('cardExpiry', {
                style: elementStyles,
                classes: elementClasses,
            });

            stripeCardCVC = stripeElements.create('cardCvc', {
                style: elementStyles,
                classes: elementClasses,
            });
        }

        stripeMountElements();

    }

    function stripeMountElements(){
        stripeCardNumber.mount('#stripe-input-card-number');
        stripeCardExpiration.mount('#stripe-input-card-expiry');
        stripeCardCVC.mount('#stripe-input-card-cvc');
    }

    function stripeSubmitPayment(){
        console.log('stripeSubmitPayment');
        stripe.createToken(stripeCardNumber).then(function(result) {
            if (result.error) {
                console.log(result);
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                console.log(result);
                Livewire.emit('stripeTokenCreated', result.token);
            }
        });
    }

</script>
<style>
    .StripeElement {
        box-sizing: border-box;

        height: 40px;

        padding: 10px 12px;

        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;

        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>

</body>
</html>
