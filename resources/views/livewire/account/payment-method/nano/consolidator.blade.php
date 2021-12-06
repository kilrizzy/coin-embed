<div>
    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">
        Ready to move funds to your consolidation address?
    </h3>
    <p>This tool will receive all pending blocks from your account, then send them to your saved consolidation address:</p>
    <p class="mb-4"><span class="px-2 py-1 rounded bg-gray-200 text-xs">{{ $consolidationAddress }}</span></p>
    <payment-method-nano-consolidate first-expected-address="{{ $this->first_expected_address }}" consolidation-address="{{ $consolidationAddress }}"></payment-method-nano-consolidate>
</div>
