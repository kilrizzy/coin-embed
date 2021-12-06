<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paymentMethodKey = $this->faker->randomElement(['nano','stripe','coinbase-commerce']);
        if($paymentMethodKey == 'nano'){
            $paymentMethodType = 'nano.nano';
        }
        if($paymentMethodKey == 'stripe'){
            $paymentMethodType = 'stripe.stripe';
        }
        if($paymentMethodKey == 'coinbase-commerce'){
            $paymentMethodType = 'coinbase-commerce.'.$this->faker->randomElement(['btc','ltc','bch']);
        }
        return [
            'uuid' => $this->faker->uuid,
            'token' => Transaction::randomToken(),
            'recipient_user_id' => null,
            'recipient_team_id' => null,
            'transactionable_id' => null,
            'transactionable_type' => null,
            'ip' => $this->faker->ipv4,
            'payment_method_key' => $paymentMethodKey,
            'payment_method_type' => $paymentMethodType,
            'currency' => 'usd',
            'amount' => $this->faker->randomFloat(5, 0.01, 50000),
            'status' => $this->faker->randomElement(['Completed', 'Pending', 'Failed']),
            'data' => null,
        ];
    }

    public function nano()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method_key' => 'nano',
                'payment_method_type' => 'nano.nano',
                'data' => '{"charge":{"address":{"id":2005,"payment_method_id":6,"nano_address":"nano_1sngpm91hmk9czs1z68rs66aeizdycu4j7g8f13b7c8p1ii9j9k8tj86fky6","nano_index":"4","last_balance_check_at":null,"last_used_at":"2020-11-26T17:27:58.000000Z","last_consolidated_at":null,"data":{},"created_at":"2020-11-26T15:39:06.000000Z","updated_at":"2020-11-26T17:27:58.000000Z"},"amount_nano":0.9802}}',
            ];
        });
    }

    public function coinbaseCommerce($type='bitcoin')
    {
        return $this->state(function (array $attributes) use($type) {
            return [
                'payment_method_key' => 'coinbase-commerce',
                'payment_method_type' => 'coinbase-commerce.'.$type,
                'data' => '{"charge":{"data":{"addresses":{"bitcoincash":"qzg2qzw56hfacd9gqtsqyklp5d88rd53jc7eareat0","litecoin":"MHjfq8bpxR9icrtFZAHcFJw2gGWSVThade","bitcoin":"34Fzaczzbrw19ttKR3PqqScoaF397sSXZH","ethereum":"0xcd5200e816711ac32c6fb0a1983019dc0fc63bc5","usdc":"0xcd5200e816711ac32c6fb0a1983019dc0fc63bc5","dai":"0xcd5200e816711ac32c6fb0a1983019dc0fc63bc5"},"code":"8QZGA8AZ","confirmed_at":"2020-11-08T01:47:15Z","created_at":"2020-11-08T01:44:05Z","description":"dfb2baf0-2163-11eb-8b95-2b324dc520ae","expires_at":"2020-11-08T02:44:05Z","hosted_url":"https:\/\/commerce.coinbase.com\/charges\/8QZGA8AZ","id":"c75630b0-394d-42be-b63c-905ae17a555f","metadata":{"widget_uuid":"2a429d00-1d7b-11eb-8250-1b13eea0bd6e","transaction_uuid":"dfb2baf0-2163-11eb-8b95-2b324dc520ae"},"name":"My Payment Widget: Hoverboard","payment_threshold":{"overpayment_absolute_threshold":{"amount":"5.00","currency":"USD"},"overpayment_relative_threshold":"0.005","underpayment_absolute_threshold":{"amount":"5.00","currency":"USD"},"underpayment_relative_threshold":"0.005"},"payments":[{"network":"bitcoincash","transaction_id":"9be6688c64a24cfda041a3a3e835260ac4acd0b0d374ee6ec8469e2c7c57417c","status":"CONFIRMED","detected_at":"2020-11-08T01:45:10Z","value":{"local":{"amount":"0.99","currency":"USD"},"crypto":{"amount":"0.00386417","currency":"BCH"}},"block":{"height":660568,"hash":"000000000000000000f32baba2a5693cb3a784c8afda6665e46f27da37589a9e","confirmations":105,"confirmations_required":1}}],"pricing":{"local":{"amount":"0.99","currency":"USD"},"bitcoincash":{"amount":"0.00386417","currency":"BCH"},"litecoin":{"amount":"0.01653445","currency":"LTC"},"bitcoin":{"amount":"0.00006602","currency":"BTC"},"ethereum":{"amount":"0.002250000","currency":"ETH"},"usdc":{"amount":"0.990000","currency":"USDC"},"dai":{"amount":"0.981126204047492454","currency":"DAI"}},"pricing_type":"fixed_price","resource":"charge","support_email":"jeffkilroy@gmail.com","timeline":[{"status":"NEW","time":"2020-11-08T01:44:06Z"},{"status":"PENDING","time":"2020-11-08T01:45:10Z","payment":{"network":"bitcoincash","transaction_id":"9be6688c64a24cfda041a3a3e835260ac4acd0b0d374ee6ec8469e2c7c57417c","value":{"amount":"0.00386417","currency":"BCH"}}},{"status":"COMPLETED","time":"2020-11-08T01:47:15Z","payment":{"network":"bitcoincash","transaction_id":"9be6688c64a24cfda041a3a3e835260ac4acd0b0d374ee6ec8469e2c7c57417c","value":{"amount":"0.00386417","currency":"BCH"}}}]},"warnings":["Missing X-CC-Version header; serving latest API version (2018-03-22)"]}}'
            ];
        });
    }

    public function stripe()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method_key' => 'stripe',
                'payment_method_type' => 'stripe.stripe',
                'data' => '{"token":{"id":"tok_1HlIvgCUYkNa0f6q9wops75z","object":"token","card":{"id":"card_1HlIvgCUYkNa0f6qDmeoMHsH","object":"card","address_city":null,"address_country":null,"address_line1":null,"address_line1_check":null,"address_line2":null,"address_state":null,"address_zip":null,"address_zip_check":null,"brand":"Visa","country":"US","cvc_check":"unchecked","dynamic_last4":null,"exp_month":4,"exp_year":2024,"funding":"credit","last4":"4242","name":null,"tokenization_method":null},"client_ip":"73.160.51.146","created":1604861276,"livemode":false,"type":"card","used":false},"charge":{"id":"ch_1HlIvjCUYkNa0f6qhi7WCh6L","object":"charge","amount":299,"amount_captured":299,"amount_refunded":0,"application":null,"application_fee":null,"application_fee_amount":null,"balance_transaction":"txn_1HlIvkCUYkNa0f6qzNyb8jy9","billing_details":{"address":{"city":null,"country":null,"line1":null,"line2":null,"postal_code":null,"state":null},"email":null,"name":null,"phone":null},"calculated_statement_descriptor":"Stripe","captured":true,"created":1604861279,"currency":"usd","customer":null,"description":"My Payment Widget: CoinEmbed","destination":null,"dispute":null,"disputed":false,"failure_code":null,"failure_message":null,"fraud_details":{},"invoice":null,"livemode":false,"metadata":{"widget_uuid":"2a429d00-1d7b-11eb-8250-1b13eea0bd6e","transaction_uuid":"e3e2b450-21f2-11eb-941a-e790c79f9253"},"on_behalf_of":null,"order":null,"outcome":{"network_status":"approved_by_network","reason":null,"risk_level":"normal","risk_score":48,"seller_message":"Payment complete.","type":"authorized"},"paid":true,"payment_intent":null,"payment_method":"card_1HlIvgCUYkNa0f6qDmeoMHsH","payment_method_details":{"card":{"brand":"visa","checks":{"address_line1_check":null,"address_postal_code_check":null,"cvc_check":"pass"},"country":"US","exp_month":4,"exp_year":2024,"funding":"credit","installments":null,"last4":"4242","network":"visa","three_d_secure":null,"wallet":null},"type":"card"},"receipt_email":null,"receipt_number":null,"receipt_url":"https:\/\/pay.stripe.com\/receipts\/acct_16R3B9CUYkNa0f6q\/ch_1HlIvjCUYkNa0f6qhi7WCh6L\/rcpt_IM0xpKaWYghrRitVH6OzLkwFalDbMpU","refunded":false,"refunds":{"object":"list","data":[],"has_more":false,"total_count":0,"url":"\/v1\/charges\/ch_1HlIvjCUYkNa0f6qhi7WCh6L\/refunds"},"review":null,"shipping":null,"source":{"id":"card_1HlIvgCUYkNa0f6qDmeoMHsH","object":"card","address_city":null,"address_country":null,"address_line1":null,"address_line1_check":null,"address_line2":null,"address_state":null,"address_zip":null,"address_zip_check":null,"brand":"Visa","country":"US","customer":null,"cvc_check":"pass","dynamic_last4":null,"exp_month":4,"exp_year":2024,"funding":"credit","last4":"4242","metadata":{},"name":null,"tokenization_method":null},"source_transfer":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"succeeded","transfer_data":null,"transfer_group":null}}'
            ];
        });
    }

}
