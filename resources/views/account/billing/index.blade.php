<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Billing') }} for {{ Auth::user()->currentTeam->name }}
        </h2>
    </x-slot>

    @include('components.specials.banner', ['link'=>false])

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="mb-4 flex justify-between">
            <div class="flex-1 mr-4">
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
                <div class="bg-white shadow sm:rounded-lg p-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 font-bold">You're currently using the free plan.</h3>
                    <p class="mb-4">Upgrade your team to a premium plan for some great benefits:</p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="ml-3 text-base leading-6 text-gray-700">
                                Enjoy CoinEmbed completely ad-free
                            </p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="ml-3 text-base leading-6 text-gray-700">
                                Increased priority on support requests
                            </p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="ml-3 text-base leading-6 text-gray-700">
                                Support ongoing development
                            </p>
                        </li>
                    </ul>
                </div>
                @endif
                <div class="mt-4 text-base text-gray-500">
                    Add additional months of premium access to your team's account for only ${{ config('app.billing_monthly_price') }}<small>/mo</small>!
                </div>
            </div>
            <div class="">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 font-bold">Increase your subscription</h3>
                @livewire('account.billing.payment-form')
            </div>
        </div>

        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 font-bold">Payments</h3>

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Months
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Amount
                    </th>
                    <th class="px-6 py-3 bg-gray-50"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" x-max="1">

                @foreach($subscriptionPayments as $subscriptionPayment)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            {{ $subscriptionPayment->created_at->format('m/d/Y g:ia') }}
                            <div class="text-sm leading-5 text-gray-500">
                                {{ $subscriptionPayment->uuid }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                                {{ $subscriptionPayment->months }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            ${{ number_format($subscriptionPayment->transaction->amount,2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

        {{ $subscriptionPayments->links() }}

    </div>

    <script src="/v1/coinembed.js"></script>

</x-app-layout>
