<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

        <div class="mb-2 justify-between flex">
            <div class="self-end mr-4">
                <label for="showOnlyTransactionsWithPayments">
                    <input id="showOnlyTransactionsWithPayments" wire:model="showOnlyTransactionsWithPayments" type="checkbox" /> Show only transactions with payments
                </label>
            </div>
            <div>
                @livewire('transactions-totals-card')
            </div>
        </div>

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Amount
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 bg-gray-50"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" x-max="1">

                @foreach($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-7 w-7">
                                    <img class="h-7 w-7 shadow rounded-full bg-blue-600" src="/img/payment-methods/icon-{{ $transaction->payment_method_key }}.png" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm leading-5 font-medium text-gray-900">
                                        <strong>
                                            @if(!empty($transaction->data->product_name))
                                                {{ $transaction->transactionable->name }} - {{ $transaction->data->product_name }}
                                            @else
                                                {{ $transaction->transactionable->name }}
                                            @endif
                                        </strong>
                                    </div>
                                    <div class="text-xs leading-5 text-gray-500">
                                        {{ $transaction->created_at->format('m/d/Y g:ia') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            <div class="text-sm leading-5 text-gray-900 font-bold">
                                ${{ number_format($transaction->amount_usd, 2, '.', ',') }}
                                @if(!empty($transaction->expected_amount) && $transaction->expected_currency != 'usd')
                                    <span class="text-gray-300 font-light inline-block ml-1">[{{ $transaction->expected_amount }} {{ strtoupper($transaction->expected_currency) }}]</span>
                                @endif
                            </div>
                            <div class="text-xs leading-5 text-gray-500 font-bold">
                                @if(!empty($transaction->paid_amount) && !empty(floatval($transaction->paid_amount)))
                                    Paid
                                    @if(!empty($transaction->paid_currency))
                                        {{ $transaction->paid_amount }} {{ strtoupper($transaction->paid_currency) }}
                                    @else
                                        {{ $transaction->paid_amount }} {{ strtoupper($transaction->payment_method_type) }}
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            @if($transaction->status_display == 'Completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 opacity-75">
                                    {{ $transaction->status_display }}
                                </span>
                            @elseif($transaction->status_display == 'Pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 opacity-75">
                                    {{ $transaction->status_display }}
                                </span>
                            @elseif($transaction->status_display == 'Failed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 opacity-75">
                                    {{ $transaction->status_display }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 opacity-75">
                                    {{ $transaction->status_display }}
                                </span>
                            @endif
                            @if($transaction->isOverpayment())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800 opacity-75">
                                    Overpayment
                                </span>
                            @endif
                            @if($transaction->isUnderpayment())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 opacity-75">
                                    Underpayment
                                </span>
                           @endif
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                            <button type="button" wire:click="openTransactionDataModal({{ $transaction }})" class="text-blue-600 hover:text-blue-900">View Details</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

        {{ $transactions->links() }}

        <x-jet-dialog-modal wire:model="openTransactionDataModal">
            <x-slot name="title">
                Transaction: #{{ $this->modalTransaction['uuid'] ?? null }}
            </x-slot>

            <x-slot name="content">
                <div><strong>Token:</strong> {{ $this->modalTransaction['token'] ?? null }}</div>
                <div><strong>Payment Method:</strong> {{ $this->modalTransaction['payment_method_key'] ?? null }} ({{ $this->modalTransaction['payment_method_type'] ?? null }})</div>
                <div><strong>Currency:</strong> {{ $this->modalTransaction['currency'] ?? null }}</div>
                <div><strong>Amount:</strong> ${{ $this->modalTransaction['amount'] ?? null }}</div>
                <div><strong>Status:</strong> #{{ $this->modalTransaction['status'] ?? null }}</div>
                @if(!empty($this->modalTransaction['blocks']))
                    <div><strong>Blocks:</strong></div>
                    @foreach($this->modalTransaction['blocks'] as $block)
                        @if(!empty($block['block_id']))
                            <div><a href="https://nanocrawler.cc/explorer/block/{{ $block['block_id'] }}" target="_blank" class="hover:underline text-xs mb-1">{{ $block['block_id'] }}</a></div>
                        @endif
                    @endforeach
                @endif
                <div><strong>Data:</strong></div>
                @if(!empty($this->modalTransaction['data']))
                    <div class="text-xs">
                    @foreach((array)$this->modalTransaction['data'] as $dataKey => $dataValue)
                        <div class="mb-2">
                            <div class="border bg-gray-100 p-1 font-bold"><strong>{{ $dataKey }}</strong></div>
                            <div class="border border-t-0 overflow-auto overflow-y-auto p-2" style="max-height: 100px;">
                                @if(is_string($dataValue))
                                    {{ $dataValue }}
                                @elseif(is_bool($dataValue))
                                    @if($dataValue) true @else false @endif
                                @else
                                    @foreach((array)$dataValue as $dataValueKey => $dataValueValue)
                                    <div>
                                        <strong>{{ $dataValueKey }}</strong><br/>
                                        @if(is_string($dataValueValue))
                                            {{ $dataValueValue }}
                                        @elseif(is_bool($dataValueValue))
                                            @if($dataValueValue) true @else false @endif
                                        @else
                                            <div class="p-2 m-2 bg-gray-100 rounded">
                                            @foreach((array)$dataValueValue as $dataValueValueKey => $dataValueValueValue)
                                                    <div>
                                                        <strong>{{ $dataValueValueKey }}</strong><br/>
                                                        @if(is_string($dataValueValueValue))
                                                            {{ $dataValueValueValue }}
                                                        @elseif(is_bool($dataValueValueValue))
                                                            @if($dataValueValueValue) true @else false @endif
                                                        @else
                                                            <?php dump($dataValueValueValue); ?>
                                                        @endif
                                                    </div>
                                            @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                    </div>
                @endif
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$set('openTransactionDataModal', null)" wire:loading.attr="disabled">
                    Close
                </x-jet-secondary-button>
            </x-slot>
        </x-jet-dialog-modal>

    </div>
</div>
