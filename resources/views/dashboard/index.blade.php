<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0 mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Getting Started</h3>

                    <p class="mt-1 mb-2 text-sm text-gray-600">Take a few moments to learn about CoinEmbed.</p>

                    <p class="mt-1 text-sm text-gray-400">
                        Remember, instead of being a middle-man with your funds, CoinEmbed ties-in your existing services and on-chain tools to allow you to keep complete control of your funds.
                    </p>
                </div>
                <div>
                    @if(Auth::user()->currentTeam->subscriptionIsActive)
                        <div class="bg-white overflow-hidden shadow rounded-lg mb-4">
                            <div class="px-4 py-5 sm:p-6 flex">
                                <div class="mr-4 self-center text-3xl text-red-600">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div>
                                    <dl>
                                        <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                            Premium plan valid until
                                        </dt>
                                        <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                                            {{ Auth::user()->currentTeam->subscription_ends_at->format('F, d Y') }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white shadow sm:rounded-lg p-4 border-2 border-red-400">
                            <h3 class="text-base leading-6 font-medium text-gray-900 mb-4 font-bold">You're currently using the free plan.</h3>
                            <p class="mb-4 text-xs"><a href="{{ url('/account/billing') }}" class="font-bold underline">Upgrade your team to a premium plan</a> for some great benefits:</p>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-500" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <p class="ml-3 text-xs leading-6 text-gray-700">
                                        Enjoy CoinEmbed completely ad-free
                                    </p>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-500" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <p class="ml-3 text-xs leading-6 text-gray-700">
                                        Increased priority on support requests
                                    </p>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-500" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <p class="ml-3 text-xs leading-6 text-gray-700">
                                        Support ongoing development
                                    </p>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">

                        <div class="mb-4">
                            <h2 class="font-bold mb-2">Configure your payment methods</h2>
                            <p>CoinEmbed allows you to use cryptocurrency directly using Nano, but we also integrate with Coinbase Commerce and Stripe to allow you to accept both traditional credit-card and cryptocurrency payments. Select the payment
                                methods you would like to accept in your widgets by <a href="{{ route('account.payment-method.index') }}" class="underline font-bold text-blue-600">configuring your Payment Methods</a>.
                            </p>
                        </div>

                        <div class="mb-4">
                            <h2 class="font-bold mb-2">Create a payment widget</h2>
                            <p class="mb-2">Widgets are customizable elements you can embed on your website to take payments. By allowing each widget to enable specific payment types, you can have access to multiple variations of payments widgets for whatever your needs are.
                            <p><a href="{{ route('account.widgets.create') }}" class="underline font-bold text-blue-600">Create a widget to start accepting payments</a>.</p>
                        </div>

                        <div>
                            <h2 class="font-bold mb-2">Add CoinEmbed To Your Website!</h2>
                            <p class="mb-2">After you have set up your payment methods and have created your first widget, use the embed code to add CoinEmbed to your website.</p>
                            <p>If you need to configure your application to update when payments are made or have additional needs, <a href="{{ url('/docs') }}" class="underline font-bold text-blue-600">take a look at our documentation</a>.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
