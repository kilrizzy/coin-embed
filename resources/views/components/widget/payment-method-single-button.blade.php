<button wire:click="setPaymentMethod('{{$paymentMethod->getName()}}')" type="button" class="w-full block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
    <div class="px-4 py-4 flex justify-center">
        <div class="flex-shrink-0">
            @include('components.widget.payment-method-icon', ['paymentMethod', $paymentMethod])
        </div>
    </div>
    <div class="text-center px-4 py-0 opacity-50 text-xs">Click here to pay via</div>
    <div class="flex justify-center px-2 py-2 border rounded-full mx-4 mt-2 mb-3 shadow">
        <div class="min-w-0 flex items-center flex-1">
            <div class="text-center flex-1 pl-5">
                <div>
                    <div class="text-sm leading-5 font-medium text-blue-600">
                        @if($paymentMethod->getName() == 'coinbase-commerce' && !empty($paymentMethodKeyLabels['coinbase-commerce']))
                                <?php
                            $methodLabels = null;
                            foreach($paymentMethodKeyLabels['coinbase-commerce'] as $paymentMethodKeyLabel){
                                $paymentMethodTypeLabel = $paymentMethod->getTypes()['coinbase-commerce.'.$paymentMethodKeyLabel] ?? $paymentMethodKeyLabel;
                                $methodLabels[] = $paymentMethodTypeLabel;
                            }
                            ?>
                            {{ implode(', ', $methodLabels) }}
                        @else
                            {{ $paymentMethod->getPaymentLabel() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div>
            <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </div>
</button>
