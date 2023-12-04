<?php

namespace App\Payments\SigmaNet\Classes;

use Illuminate\Contracts\Support\Arrayable;

class Order implements Arrayable
{

    public function __construct
    (
        public $amount,
        public $currency,
        public $description = '',
    )
    {

    }

    public function toArray()
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
        ];
    }
}
