@extends('layouts.front')

@section('content')

            @include('components.specials.banner', ['link'=>true])

            <div class="relative">
                <div class="mx-auto max-w-7xl w-full pt-16 pb-20 text-center lg:py-48 lg:text-left">
                    <div class="px-4 lg:w-1/2 sm:px-8 xl:pr-16">
                        <h2 class="text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl lg:text-5xl xl:text-6xl">
                            Crypto &amp; Fiat
                            <br>
                            <span class="text-blue-600 text-lg text-3xl sm:text-4xl md:text-5xl lg:text-4xl xl:text-5xl">♥️ together at last ♥️️</span>
                        </h2>
                        <p class="mt-3 max-w-md mx-auto text-lg text-gray-500 sm:text-lg md:mt-5 md:max-w-3xl">
                            Use <strong>CoinEmbed</strong> to create embeddable widgets for easy cryptocurrency payments. <br/><br/> We connect directly to <i>Coinbase Commerce</i>, <i>Stripe</i>, or a <i>Nano</i> wallet so all payments <strong>come directly to you!</strong>
                        </p>
                        <div class="mt-10 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                @if(Auth::check())
                                <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10">
                                    Jump Back In
                                </a>
                                @else
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10">
                                    Get started
                                </a>
                                @endif
                            </div>
                            <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                                <a href="javascript:alert('Coming Soon!')" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-blue-600 bg-white hover:text-blue-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10">
                                    Live demo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative px-4 w-full lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 lg:h-full bg-blue-600 flex flex-col justify-center">

                    <div class="text-center">
                        <h3 class="text-lg leading-6 font-medium mb-4 font-bold text-white opacity-75">Give CoinEmbed a test by sending us a tip!</h3>

                        <div class="mx-auto flex justify-center self-center">
                            @livewire('account.donate.donate-form')
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-blue-100 pt-20">
                <div class="text-center mb-20">
                    <h2 class="text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl">
                        A beautiful dashboard
                        <br>
                        <span class="text-blue-600">to view payments</span>
                    </h2>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 flex flex-col">
                        <div class="flex-1"></div>
                        <div class="flex-1 w-full bg-blue-800"></div>
                    </div>
                    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 pb-20">
                        <img class="relative rounded-lg shadow-lg" src="/img/front/payments-screenshot.png" alt="Screenshot">
                    </div>
                </div>
            </div>

            <div class="bg-gray-700">
                <div class="py-12 sm:pt-16 lg:pt-20">
                    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-10">
                            <h2 class="text-4xl tracking-tight leading-10 font-extrabold text-gray-100 sm:text-5xl sm:leading-none md:text-6xl">
                                Easily to add to your website
                            </h2>
                        </div>
                        <div class="bg-gray-900 shadow rounded p-8 mb-2 text-gray-100 overflow-hidden">
                            <?php
                            $code = '<div
    rel="coinembed-widget"
    data-ce-id="1111-1111-1111-1111"
    data-ce-amount="0.99"
    data-ce-currency="usd"
    data-ce-name="Hoverboard"
    data-ce-callback="paymentComplete"
></div>';
                            ?>
                            <pre>{{ $code }}</pre>
                        </div>
                        <div class="text-center text-lg text-gray-100">
                            <em>"OMG you added a payment widget to your website you must be so smart"</em> - <strong>Your crush</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-100">
                <div class="pt-12 sm:pt-16 lg:pt-20">
                    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center">
                            <h2 class="text-3xl leading-9 font-extrabold text-gray-900 sm:text-4xl sm:leading-10 lg:text-5xl lg:leading-none">
                                One monthly fee, <span class="underline">no cost per transaction</span>
                            </h2>
                            <p class="mt-4 text-xl leading-7 text-gray-600">
                                CoinEmbed directly connects to CoinbaseCommerce, Stripe, or your Nano wallet so all payments come directly to you!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 bg-gray-100 pb-16 sm:mt-12 sm:pb-20 lg:pb-28">
                    <div class="relative">
                        <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center">
                            <div class="max-w-lg mx-auto rounded-lg shadow-lg overflow-hidden lg:max-w-none lg:flex">
                                <div class="py-8 px-6 text-center bg-gray-50 lg:flex-shrink-0 lg:flex lg:flex-col lg:justify-center lg:p-12">
                                    <div class="mt-4 flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900">
              <span>
                ${{ config('app.billing_monthly_price') }}
              </span>
                                        <span class="ml-3 text-xl leading-7 font-medium text-gray-500">
                /mo
              </span>
                                    </div>
                                    <div class="mt-6">
                                        <div class="rounded-md shadow">
                                            <a href="{{ route('register') }}" class="flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                Get Access
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection
