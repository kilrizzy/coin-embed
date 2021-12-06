<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Methods') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul>
                @foreach(\App\Support\PaymentMethods\PaymentMethodRepository::all() as $paymentMethod)
                <li class="@if(!$loop->first && !$loop->last) border-t border-gray-200 @endif @if($loop->last) border-t border-gray-200 @endif">
                    <a href="{{ url('/account/payment-method/'.$paymentMethod->getName()) }}" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="flex items-center px-4 py-4 sm:px-6">
                            <div class="min-w-0 flex-1 flex items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 @if(!Auth::user()->currentTeam->hasPaymentMethod($paymentMethod->getName())) opacity-25 @endif" src="/img/payment-methods/icon-{{ $paymentMethod->getName() }}.png" alt="">
                                </div>
                                <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                    <div>
                                        <div class="text-sm leading-5 font-medium text-blue-600 truncate">{{ $paymentMethod->getLabel() }}</div>
                                        <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
                                            <span class="truncate">{{ $paymentMethod->getShortDescription() }}</span>
                                        </div>
                                    </div>
                                    <div class="hidden md:block self-center">
                                        <div>
                                            @if(Auth::user()->currentTeam->hasPaymentMethod($paymentMethod->getName()))
                                                <div class="flex items-center text-sm leading-5 text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400" x-description="Heroicon name: check-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Payment Method Enabled
                                                </div>
                                            @else
                                                <div class="flex items-center text-sm leading-5 text-gray-500">
                                                    <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                                    <div>
                                                        Click to setup payment method
                                                    </div>
                                                </div>
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
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

</x-app-layout>
