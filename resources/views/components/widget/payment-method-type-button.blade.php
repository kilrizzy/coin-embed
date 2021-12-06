<button wire:click="setPaymentMethodType('{{$typeKey}}')" type="button" class="w-full block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
    <div class="flex items-center px-4 py-4">
        <div class="min-w-0 flex-1 flex items-center">
            <div class="flex-1 px-4 text-left">
                <div>
                    <div class="text-sm leading-5 font-medium text-blue-600">
                        {{ $typeLabel }}
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