<?php

namespace App\Payments\SigmaNet\Classes;

use Illuminate\Contracts\Support\Arrayable;

class Payout implements Arrayable
{

    public function __construct
    (
        public string|int $transaction_id,
        public int $wallet_id,
        public PayoutMethodData $payoutMethodData,
        public string $fee_type,
        public Order $order
    )
    {

    }

    public function toArray()
    {
        return [
            'transaction_id' => $this->transaction_id,
            'wallet_id' => $this->wallet_id,
            'payout_method_data' => $this->payoutMethodData->toArray(),
            'fee_type' => $this->fee_type,
            'order' => $this->order->toArray(),
        ];
    }
}
