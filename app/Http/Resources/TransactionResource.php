<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'created_at' => $this->created_at,
            'token' => $this->token,
            'amount_usd' => $this->amount,
            'currency' => $this->expected_currency,
            'amount_expected' => $this->expected_amount,
            'amount_received' => $this->paid_amount,
            'is_overpayment' => $this->isOverpayment(),
            'is_underpayment' => $this->isUnderpayment(),
            'widget_uuid' => $this->transactionable->uuid ?? null,
            'ip' => $this->ip,
            'payment_method_key' => $this->payment_method_key,
            'payment_method_type' => $this->payment_method_type,
            'status' => $this->status,
            'blocks' => $this->blocks,
            'data' => $this->data,
        ];
    }
}
