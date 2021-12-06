<div class="bg-white overflow-hidden shadow rounded-lg flex justify-between">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                <i class="fas fa-coins text-white"></i>
            </div>
            <div class="ml-4 flex-1">
                <dt class="text-sm font-medium text-gray-500 ">
                    Total Payments
                </dt>
                <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">
                        {{ $paymentsCount }} (${{ number_format($totalUSDAmount,2) }})
                    </div>
                </dd>
            </div>
        </div>
    </div>
    <div class="bg-gray-50 px-4 py-4 sm:px-6">
        <div class="text-xs">
            @foreach($totalAmounts as $totalAmountCurrency => $totalAmount)
            <div class="mb-1">
                @if($totalAmountCurrency == 'usd')
                    <div><span class="font-bold uppercase">USD:</span> ${{ number_format($totalAmount['amount_usd'],2) }}</div>
                @else
                    <div><span class="font-bold uppercase">{{ $totalAmountCurrency }}:</span> {{ $totalAmount['amount'] }} (${{ number_format($totalAmount['amount_usd'],2) }})</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
