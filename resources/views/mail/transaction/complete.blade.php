@component('mail::message')
# Transaction Complete!

You have received a new transaction

**Id:** {{ $transaction->uuid }}

**Widget:** {{ $transaction->transactionable->name }}

**Payment Method:** {{ $transaction->payment_method_type }}

**Amount (USD):** ${{ number_format($transaction->amount, 2) }}

**Currency:** {{ $transaction->expected_currency }}

**Amount Expected:** {{ $transaction->expected_amount }}

**Amount Received:** {{ $transaction->paid_amount }}

@component('mail::button', ['url' => url('/account/transaction')])
Click here to view
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
