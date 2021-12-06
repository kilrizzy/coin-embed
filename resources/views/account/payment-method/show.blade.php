<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Method').' - '.$paymentMethod->getLabel() }}
        </h2>
    </x-slot>

    @if($paymentMethod->getName() == 'stripe')
        <livewire:account.payment-method.stripe />
    @endif

    @if($paymentMethod->getName() == 'coinbase-commerce')
        <livewire:account.payment-method.coinbase-commerce />
    @endif

    @if($paymentMethod->getName() == 'nano')
        <livewire:account.payment-method.nano />
    @endif

</x-app-layout>
