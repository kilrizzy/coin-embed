@component('components.widget.body-container')
    @if(isset($transaction->data->payment_marked_done) && !empty($transaction->data->payment_marked_done))
        <div class="px-4 mb-4">
            <div class="text-center mb-2 pt-2">
                <svg class="w-20 h-20 mx-auto text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-green-400 text-base mb-4 text-center"><strong>Transaction Complete!</strong></p>
            <p class="text-xs mb-1"><strong>Transaction Id: </strong> <br/>{{ $transaction->uuid }}</p>
        </div>
    @else
        @if(isset($transaction->data->charge->error))
            <div class="p-4 text-center text-sm text-red-400">
                <strong>Error:</strong> {{ $transaction->data->charge->error->message ?? 'Error' }}
            </div>
        @endif
        @if(!isset($transaction->data->charge))
            <div class="p-4 text-center text-sm text-gray-400">
                Creating a new charge...
            </div>
        @else
            <div class="p-4 pt-2" wire:poll.5s="coinbaseCommerceCheckPayment">
                @if(isset($transaction->data->charge->data->payments) && !empty($transaction->data->charge->data->payments))
                    <?php
                    $pendingPaymentCount = 0;
                    $confirmedPaymentCount = 0;
                    $otherPaymentCount = 0;
                    $pendingPaymentAmount = 0;
                    $confirmedPaymentAmount = 0;
                    $otherPaymentAmount = 0;

                    foreach($transaction->data->charge->data->payments as $payment){
                        if($payment->status == 'PENDING'){
                            $pendingPaymentCount++;
                            $pendingPaymentAmount+=$payment->value->crypto->amount ?? 0;
                        }else if($payment->status == 'CONFIRMED'){
                            $confirmedPaymentCount++;
                            $confirmedPaymentAmount+=$payment->value->crypto->amount ?? 0;
                        }else{
                            $otherPaymentCount++;
                            $otherPaymentAmount+=$payment->value->crypto->amount ?? 0;
                        }
                    }
                    $totalPaymentAmount = $pendingPaymentAmount+$confirmedPaymentAmount+$otherPaymentAmount;
                    ?>
                    @if(!empty($pendingPaymentCount))
                            <div class="p-2 rounded bg-yellow-200 text-xs text-center mb-2"><strong>Pending Payments ({{ $pendingPaymentCount }}):</strong> {{ $pendingPaymentAmount }} {{ $this->coinbaseCommerceSendCurrencyName }}</div>
                    @endif
                    @if(!empty($totalPaymentAmount))
                            <div class="p-2 rounded bg-green-300 text-xs text-center mb-2"><strong>Total Payment {{ $totalPaymentAmount }}:</strong> {{ $this->coinbaseCommerceSendCurrencyName }}</div>
                            <p class="text-xs mb-1 text-center"><button wire:click="coinbaseCommerceMarkPaymentDone()" class="underline">If you've finished sending <br/>you can click here to continue</button></p>
                    @endif
                @endif
                <div class="mb-0 -mt-1 text-center">
                    <span class="text-2xl">{{ $this->coinbaseCommerceSendCurrencyAmount }}</span>
                    <span class="text-lg font-bold">{{ $this->coinbaseCommerceSendCurrencyName }}</span>
                </div>
                <div class="mb-1 -mt-1">
                    <input type="text" value="{{ $this->coinbaseCommerceSendAddress }}" class="border rounded text-xs bg-gray-100 shadow text-gray-400 p-1 w-full outline-none text-center" style="font-size:10px;" />
                </div>
                <div class="text-center">
                    <img src="/qr/{{ $this->coinbaseCommerceSendAddress }}" class="mx-auto" style="width:140px; height:140px;" />
                </div>
            </div>
        @endif

    @endif

@endcomponent
@component('components.widget.footer-container')
    @if(isset($transaction->data->payment_marked_done) && !empty($transaction->data->payment_marked_done))
        Transaction complete!
    @else
        @slot('actionLeft')
            <button wire:click="returnToSelectPaymentType" class="cursor-pointer h-5 w-5 opacity-50 hover:opacity-100">
                @include('components.widget.back-icon')
            </button>
        @endslot
        Complete your payment <br/>by sending to the address above
    @endif
@endcomponent
