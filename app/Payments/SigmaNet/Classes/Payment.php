<?php

namespace App\Payments\SigmaNet\Classes;

use Illuminate\Contracts\Support\Arrayable;

class Payment implements Arrayable
{

    public function __construct
    (
        public int $partner_payment_id,
        public Order $order,
        public Settings $settings,
        public Receipt $receipt
    )
    {

    }

    public function toArray()
    {
        return [
            'partner_payment_id' => $this->partner_payment_id,
            'order' => $this->order->toArray(),
            'settings' => $this->settings->toArray(),
            'receipt' => $this->receipt->toArray(),
        ];
    }
}
