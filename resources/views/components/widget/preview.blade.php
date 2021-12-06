<?php
    $paymentMethodKeys = [];
    if(!empty($widgetData['settings']['payment_method_types'])){
        foreach($widgetData['settings']['payment_method_types'] as $paymentMethodTypeFull){
            $keyParts = explode('.',$paymentMethodTypeFull);
            if(!isset($paymentMethodKeys[$keyParts[0]]) ){
                $paymentMethodKeys[$keyParts[0]] = [];
            }
            $paymentMethodKeys[$keyParts[0]][] = $keyParts[1];
        }
    }
    $paymentMethodKeyLabels = $paymentMethodKeys;
?>
@component('components.widget.widget-container')
    @include('components.widget.widget-header',[
        'displayProductImageURL' => 'https://images.unsplash.com/photo-1588178553725-80283e06600e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=150&h=150&q=100',
        'displayProductName' => 'Flux Capacitor',
        'displayAmount' => '9.99',
    ])
        <div class="bg-white">
            @if(empty($paymentMethodKeys))
                @component('components.widget.body-container')
                    <div class="text-center text-xs opacity-50 p-4">No payment methods available</div>
                @endcomponent
            @else
                @if(empty($transaction['paymentMethodKey']))
                    @component('components.widget.body-container')
                        @if(count($paymentMethodKeys) == 1)
                            <?php
                            $paymentMethod = \App\Support\PaymentMethods\PaymentMethodRepository::findByName(array_key_first($paymentMethodKeys));
                            ?>
                            <div class="border-t border-gray-200">
                                @include('components.widget.payment-method-single-button',['paymentMethod'=>$paymentMethod])
                            </div>
                        @else
                            @foreach($paymentMethodKeys as $paymentMethodKey => $paymentMethodTypes)
                                <?php
                                $paymentMethod = \App\Support\PaymentMethods\PaymentMethodRepository::findByName($paymentMethodKey);
                                ?>
                                <div class="@if(!$loop->first) border-t border-gray-200 @endif">
                                    @include('components.widget.payment-method-button',['paymentMethod'=>$paymentMethod])
                                </div>
                            @endforeach
                        @endif
                    @endcomponent
                    @component('components.widget.footer-container')
                        Select a payment method
                    @endcomponent
                @endif
                @if(!empty($transaction) && !empty($transaction['paymentMethodKey']))
                    @if(empty($transaction['paymentMethodType']))
                            <?php
                            $paymentMethod = \App\Support\PaymentMethods\PaymentMethodRepository::findByName($transaction['paymentMethodKey']);
                            ?>
                            @foreach($paymentMethod->getTypes() as $typeKey => $typeLabel)
                                <?php $paymentMethodTypesAvailable = $paymentMethodKeys[$paymentMethod->getName()]; ?>
                                <?php $typeKeyWithoutName = str_replace($paymentMethod->getName().'.','', $typeKey); ?>
                                @if(in_array($typeKeyWithoutName,$paymentMethodTypesAvailable))
                                    <div class="border-t border-gray-200">
                                        @include('components.widget.payment-method-type-button',[
                                            'typeKey'=>$typeKey,
                                            'typeLabel'=>$typeLabel,
                                        ])
                                    </div>
                                @endif
                            @endforeach
                                @component('components.widget.footer-container')
                                    @slot('actionLeft')
                                        <button wire:click="returnToSelectPaymentMethod" class="cursor-pointer h-5 w-5 opacity-50 hover:opacity-100">
                                            @include('components.widget.back-icon')
                                        </button>
                                    @endslot
                                    Select a payment method type
                                @endcomponent
                    @else
                            @component('components.widget.body-container')
                                <?php $paymentMethodTypeLabel = explode('.',$transaction['paymentMethodType'])[1]; ?>
                                <div class="text-center text-xs opacity-50 p-4">User will complete payment via {{ $paymentMethodTypeLabel }}</div>
                            @endcomponent
                                @component('components.widget.footer-container')
                                    @slot('actionLeft')
                                        <button wire:click="returnToSelectPaymentMethod" class="cursor-pointer h-5 w-5 opacity-50 hover:opacity-100">
                                            @include('components.widget.back-icon')
                                        </button>
                                    @endslot
                                    Complete payment
                                @endcomponent
                    @endif
                @endif
            @endif
        </div>
@endcomponent
