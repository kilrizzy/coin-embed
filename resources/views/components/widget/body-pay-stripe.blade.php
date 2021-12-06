@component('components.widget.body-container')
    @if(empty($transaction->data->token) || !isset($transaction->data->charge->id))
        <div>
            <div class="px-4 mb-2">
                <label for="stripe-input-card-number" class="font-bold text-xs">Card number</label>
                <div id="stripe-input-card-number" class=""></div>
            </div>
        </div>

        <div class="flex px-4 mb-4">
            <div class="w-1/2">
                <label for="stripe-input-card-expiry" class="font-bold text-xs">Expiration</label>
                <div id="stripe-input-card-expiry" class=""></div>
            </div>
            <div class="w-1/2 ml-4">
                <label for="stripe-input-card-cvc" class="font-bold text-xs">CVC</label>
                <div id="stripe-input-card-cvc" class=""></div>
            </div>
        </div>
        <div id="card-errors" role="alert" class="text-red-600 text-xs text-center p-2"></div>

        <div class="px-4 mb-4">
            <button-ui onclick="javascript: stripeSubmitPayment()" type="button" theme="primary" class="w-full">Submit Payment</button-ui>
        </div>
    @else

        <div class="px-4 mb-4">
            <div class="text-center mb-2 pt-2">
                <svg class="w-20 h-20 mx-auto text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-green-400 text-base mb-4 text-center"><strong>Transaction Complete!</strong></p>
            <p class="text-xs mb-1"><strong>Transaction Id: </strong> {{ $transaction->uuid }}</p>
            <p class="text-xs mb-1"><strong>Status: </strong> {{ $transaction->data->charge->status }}</p>
            @if(!empty($transaction->data->charge->failure_code))
                <p class="text-sm text-red-600 mb-1"><strong>Failure Code: </strong> {{ $transaction->data->charge->failure_code }}</p>
                <p class="text-sm text-red-600 mb-1"><strong>Failure Code: </strong> {{ $transaction->data->charge->failure_message }}</p>
            @endif
        </div>

    @endif

@endcomponent
@component('components.widget.footer-container')
    @if(isset($transaction->data->charge->id))
        Transaction complete!
    @else
        @slot('actionLeft')
            <button wire:click="returnToSelectPaymentMethod" class="cursor-pointer h-5 w-5 opacity-50 hover:opacity-100">
                @include('components.widget.back-icon')
            </button>
        @endslot
        Complete your payment above
    @endif
@endcomponent
