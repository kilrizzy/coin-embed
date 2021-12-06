@extends('layouts.front')

@section('content')

    <div class="relative">
        <div class="mx-auto max-w-7xl w-full pt-8 pb-8 text-center lg:text-left prose max-w-none">

            <h1>Documentation</h1>

            <div class="text-center">
                <a href="#embed">Embed Widget</a> |
                <a href="#authentication">Authentication</a> |
                <a href="#transactions-show">Get Transaction</a> |
                <a href="#webhooks">Webhooks</a>
            </div>

            <h2 id="embed">Embed Widget</h2>

            <p class="mb-4 pt-8">Add this line of code within the <code>&lt;head&gt;</code> tags of your website</p>

            <div>
                <pre><?php echo htmlentities('<script src="'.url('/v1/coinembed.js').'"></script>'); ?></pre>
            </div>

            <p class="mb-4">Add this piece of code where you would like the button that triggers the widget to appear</p>

            <div class="mb-4">
                <pre><?php echo htmlentities('<div rel="coinembed-widget" data-widget-id="[WIDGET_ID]"></div>'); ?></pre>
            </div>

            <h3>Available Options</h3>
            <p>A number of properties are available when rendering a widget:</p>
            <table>
                <tr>
                    <th>id</th>
                    <td>The widget id from your dashboard</td>
                </tr>
                <tr>
                    <th>amount</th>
                    <td>The amount of the expected payment (in usd)</td>
                </tr>
                <tr>
                    <th>currency</th>
                    <td>Currently only "usd" supported</td>
                </tr>
                <tr>
                    <th>name</th>
                    <td>A title to be displayed in the header of the payment widget</td>
                </tr>
                <tr>
                    <th>image</th>
                    <td>Image url to be displayed in the header of the payment widget</td>
                </tr>
                <tr>
                    <th>callback</th>
                    <td>The method to be called after a payment has been marked complete. Passes a parameter indicating the transaction id used for later validation.</td>
                </tr>
            </table>

            <p>To add these into your widget, simply pass them as data attributes into the element:</p>

            <div class="mb-4">
                <pre><?php echo htmlentities('<div
    rel="coinembed-widget"
    data-ce-id="1111-1111-1111-1111"
    data-ce-amount="0.99"
    data-ce-currency="usd"
    data-ce-name="Hoverboard"
    data-ce-image="https://www.fillmurray.com/100/100"
    data-ce-callback="paymentComplete"
></div>'); ?></pre>

            <p>When receiving a callback, a value will be returned containing the id of the created transaction. </p>

                <div>
                    <pre><?php echo htmlentities('<script>
function paymentComplete(transactionId){
    alert("A payment has been completed with the transaction id: "+transactionId);
    // Validate the transaction server-side
}
</script>'); ?></pre>
                </div>

            </div>



            <h2 id="authentication">Authentication</h2>
            <p>To make calls to the API, you must create an api token by visiting the API Tokens page from the user menu. Give the token a name and click create, the authentication token will be displayed once.</p>
            <p>When making calls to the API, simply add the authentication token you created to the Authorization headers of your request as a bearer token:</p>
            <p><pre>Authorization: Bearer [API_TOKEN]</pre></p>

            <h2 id="transactions-show">Get Transaction by returned token</h2>
            <p>When you use the "callback" widget property, you define a method to be called that will receive an attribute with a token to retrieve data about this transaction.</p>
            <div>
                <pre>GET {{ url('/api/transaction/[TRANSACTION_TOKEN]') }}</pre>
            </div>
            <p>Example Response:</p>
            <div id="transaction-model">
                <pre>{
    "uuid": "33c995e0-22bb-11eb-93a3-776367d0b665",
    "created_at": "2020-11-09T18:41:38.000000Z",
    "token": "coinembed_fsLEOPGvQNfnYFs2MINqpMB65zUwepEPC5vSPrR5",
    "amount_usd": "0.990",
    "currency": "btc",
    "amount_expected": "0.05",
    "amount_received": "0.045",
    "is_overpayment": false,
    "is_underpayment": false,
    "widget_uuid": "900b5010-1dd8-11eb-a83c-890dd5f5ff33",
    "ip": "172.16.0.0",
    "payment_method_key": "coinbase-commerce",
    "payment_method_type": "coinbase-commerce.bitcoin",
    "status": "Completed",
    "data": {}
}</pre>
            </div>

            <h3>Transaction Response Object</h3>
            <table>
                <tr>
                    <th>uuid</th>
                    <td>The direct id of the transaction</td>
                </tr>
                <tr>
                    <th>created_at</th>
                    <td>Date the transaction was initiated</td>
                </tr>
                <tr>
                    <th>token</th>
                    <td>The token generated and returned to the callback method</td>
                </tr>
                <tr>
                    <th>amount_usd</th>
                    <td>The amount of the local currency used</td>
                </tr>
                <tr>
                    <th>currency</th>
                    <td>The name of the currency paid with</td>
                </tr>
                <tr>
                    <th>amount_expected</th>
                    <td>The amount of the currency calculated to be paid</td>
                </tr>
                <tr>
                    <th>amount_received</th>
                    <td>The amount of currency that has been paid</td>
                </tr>
                <tr>
                    <th>is_overpayment</th>
                    <td>If the payment contains more than the expected amount</td>
                </tr>
                <tr>
                    <th>is_underpayment</th>
                    <td>If a payment has been made but is less than the expected amount</td>
                </tr>
                <tr>
                    <th>widget_uuid</th>
                    <td>The id of the widget used to complete the transaction</td>
                </tr>
                <tr>
                    <th>ip</th>
                    <td>IP Address of the purchaser</td>
                </tr>
                <tr>
                    <th>payment_method_key</th>
                    <td>Selected payment method</td>
                </tr>
                <tr>
                    <th>payment_method_type</th>
                    <td>The selected sub-type of the payment method if available</td>
                </tr>
                <tr>
                    <th>status</th>
                    <td>The status of the transaction. <br/>
                        <span class="text-sm">Available statuses: [New, Pending, Completed, Failed]</span></td>
                </tr>
                <tr>
                    <th>data</th>
                    <td>Additional charge or payment data stored to the transaction from the service selected. See documentation of the service (Coinbase Commerce, Stripe, etc.) for more information</td>
                </tr>
            </table>

            <h2 id="webhooks">Webhooks</h2>
            <p>When users pay via slower cryptocurrencies like BTC or ETH, CoinEmbed gives the user an option to mark the payment as complete so they can continue without waiting for the transaction to fully confirm.</p>
            <p>For cases like these you may consider using a webhook URL. When creating or editing a widget, you can enter a webhook URL to your website or application that CoinEmbed will post data to when the transaction has been confirmed. Any transactions for this widget will then make a POST request to your endpoint with the following information.</p>
            <div>
                <pre>{
    "action": "transaction-completed",
    "transaction": <a href="#transaction-model">[the transaction model]</a>
}</pre>
            </div>

        </div>
    </div>




@endsection
