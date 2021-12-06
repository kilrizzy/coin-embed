@if($debug)
<div id="debug" class="text-xs opacity-25 pt-2">
    <p>Transaction id: {{ $transaction->uuid ?? null }}</p>
    <p>Payment Type: {{ $transaction->payment_method_type ?? null }}</p>
</div>
@endif