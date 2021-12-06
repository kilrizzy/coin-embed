<div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Preview</h3>
                <div class="my-8">
                    @include('components.widget.preview', [
                    'widgetData' => $widgetData,
                    'transaction' => $previewTransaction,
                    ])
                </div>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Widget Details</h3>
            <div class="shadow sm:rounded-md sm:overflow-hidden my-8">
                <div class="px-4 py-5 bg-white sm:p-6">

                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-3 sm:col-span-2">
                            <label for="title" class="font-bold block text-sm font-medium leading-5 text-gray-700">
                                Name
                            </label>
                            <div class="mt-1 flex flex-1 rounded-md shadow-sm">
                                <input id="name" wire:model="widgetData.name" class="form-input flex-1 block w-full rounded-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="Website Payment Widget">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="about" class="font-bold block text-sm leading-5 font-medium text-gray-700">
                            Payment Methods
                        </label>
                        <div class="mt-2 text-sm text-gray-500">
                            @foreach(\App\Support\PaymentMethods\PaymentMethodRepository::all() as $paymentMethod)
                                @if(Auth::user()->currentTeam->hasPaymentMethod($paymentMethod->getName()))
                                    <div class="mb-4">
                                        <div class="mb-1 text-xs font-bold opacity-50">{{ $paymentMethod->getLabel() }}</div>
                                        @foreach($paymentMethod->getTypes() as $paymentMethodTypeKey => $paymentMethodTypeLabel)
                                            <button type="button" wire:click="togglePaymentMethod('{{ $paymentMethodTypeKey }}')" class="mt-1 relative rounded-md shadow-sm border block w-full">
                                                <div class="block w-full p-4 sm:text-sm sm:leading-5 flex">
                                                    <div class="self-center mr-4">
                                                        @if(in_array($paymentMethodTypeKey, $widgetData['settings']['payment_method_types']))
                                                            <i class="fas fa-circle text-green-600"></i>
                                                        @else
                                                            <i class="far fa-circle text-gray-400"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 font-bold text-left">
                                                        {{ $paymentMethodTypeLabel }}
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="title" class="font-bold block text-sm font-medium leading-5 text-gray-700">
                            (optional) Post to a webhook URL when transactions are completed <a href="{{ url('/docs#webhooks') }}" target="_blank" class="text-xs underline text-blue-600">(Read More)</a>
                        </label>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <div class="mt-1 flex flex-1 rounded-md shadow-sm">
                                    <input id="name" wire:model="widgetData.settings.webhook_url" class="form-input flex-1 block w-full rounded-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="https://">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>
                        <button-ui type="button" wire:click="save" theme="primary">Save</button-ui>
                    </div>

                </div>
            </div>
            <div class="text-right mb-8">
                @if(!empty($widget) && !empty($widget->uuid))
                    <button-ui type="button" onclick="confirm('This will delete the widget. If you have this widget embedded on your website it will no longer work. Continue?') || event.stopImmediatePropagation()" wire:click="delete" theme="danger" size="sm">Delete Widget</button-ui>
                @endif
            </div>
        </div>
    </div>
</div>
